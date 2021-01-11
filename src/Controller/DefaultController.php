<?php
namespace App\Controller;
use App\Entity\Autores;
use App\Entity\Editoriales;
use App\Entity\Libros;
use App\Entity\AutoresHasLibros;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

// ...

class DefaultController extends AbstractController 
{
    /**
     * @Route("/")
     */
    public function index(): Response
    {
        $editoriales=$this->buscarEditorialesAction(1);
        return $this->render('base.html.twig', [
            'editoriales'=>$editoriales
        ]);
    }

    /**
     * @Route("/buscarlibros")
     */
    public function buscarLibrosAction($indicador=0)
    {
        $sql = " 
            SELECT 
            a.*, 
            b.nombre AS editorial 
            FROM libros a 
            JOIN editoriales b ON(b.id=a.editoriales_id)
        ";
        $manager = $this->getDoctrine()->getManager();
        $conn = $manager->getConnection();
        $result= $conn->query($sql)->fetchAll();
        return new JsonResponse(['libros'=>$result]);
    }

    /**
     * @Route("/buscarautoresrelacionados")
     */
    public function buscarAutoresRelacionadosAction(Request $request)
    {
        $ISBN=$request->get('isbn');
        if(!$ISBN){
            return new JsonResponse(['relacionados'=>[]]);
        }
        $sql = " 
            SELECT 
            a.id, 
            a.autores_id, 
            a.libros_isbn, 
            b.nombre, 
            b.apellidos 
            FROM autores_has_libros a 
            JOIN autores b ON(b.id=a.autores_id)
            WHERE 
            a.libros_isbn=$ISBN 
        ";
        $manager = $this->getDoctrine()->getManager();
        $conn = $manager->getConnection();
        $result= $conn->query($sql)->fetchAll();
        return new JsonResponse(['relacionados'=>$result]);
    }

    /**
     * @Route("/eliminarautorrelacionado")
     */
    public function eliminarAutorRelacionadoAction(Request $request)
    {        
        $id=$request->get('id');
        $isbn=$request->get('isbn');
        $entityManager = $this->getDoctrine()->getManager();
        $respuesta=[
            'estado'=>1
        ];
        $relacion=null;
        if($id){
            $relacion = $this->getDoctrine()->getRepository(AutoresHasLibros::class)->find($id);
        }
        if(!$id){
            $respuesta['estado']=0;
            $respuesta['mensaje']="- El autor seleccionado no es válido o no existe";
            return new JsonResponse($respuesta);
        }
        $entityManager->remove($relacion);
        $entityManager->flush();
        $sql = " 
            SELECT 
            a.id, 
            a.autores_id, 
            a.libros_isbn, 
            b.nombre, 
            b.apellidos 
            FROM autores_has_libros a 
            JOIN autores b ON(b.id=a.autores_id)
            WHERE 
            a.libros_isbn=$isbn 
        ";
        $manager = $this->getDoctrine()->getManager();
        $conn = $manager->getConnection();
        $respuesta['estado']=1;
        $respuesta['relacionados']= $conn->query($sql)->fetchAll();
        return new JsonResponse($respuesta);
    }

    /**
     * @Route("/guardarautoresrelacionados")
     */
    public function guardarAutorRelacionadoAction(Request $request)
    {        
        $ISBN=$request->get('isbn');
        $autores_id=$request->get('autores_id');
        $entityManager = $this->getDoctrine()->getManager();
        $relacion = new AutoresHasLibros();   
        $relacion->setLibrosISBN($ISBN);
        $relacion->setAutoresId($autores_id);
        $entityManager->persist($relacion);
        $entityManager->flush();
        $sql = " 
            SELECT 
            a.id, 
            a.autores_id, 
            a.libros_isbn, 
            b.nombre, 
            b.apellidos 
            FROM autores_has_libros a 
            JOIN autores b ON(b.id=a.autores_id)
            WHERE 
            a.libros_isbn=$ISBN 
        ";
        $manager = $this->getDoctrine()->getManager();
        $conn = $manager->getConnection();
        $result= $conn->query($sql)->fetchAll();
        return new JsonResponse(['relacionados'=>$result]);
    }

    /**
     * @Route("/guardarlibro")
     */
    public function guardarLibroAction(Request $request)
    {        
        $ISBN=$request->get('ISBN');
        $titulo=$request->get('titulo');
        $sinopsis=$request->get('sinopsis');
        $n_paginas=$request->get('n_paginas');
        $editorial_id=$request->get('editorial_id');
        $entityManager = $this->getDoctrine()->getManager();
        if(!$ISBN){
            $autor = new Libros();            
        }else{
            $autor = $this->getDoctrine()->getRepository(Libros::class)->find($ISBN);
        }
        $autor->setTitulo($titulo);
        $autor->setSinopsis($sinopsis);
        $autor->setNPaginas($n_paginas);
        $autor->setEditorialesId($editorial_id);
        $entityManager->persist($autor);
        $entityManager->flush();
        return new JsonResponse(['libros'=>$this->buscarLibrosAction(1)]);
    }

    /**
     * @Route("/eliminarlibro")
     */
    public function eliminarLibroAction(Request $request)
    {        
        $ISBN=$request->get('isbn');
        $entityManager = $this->getDoctrine()->getManager();
        $respuesta=[
            'estado'=>1
        ];
        $libro=null;
        if($ISBN){
            $libro = $this->getDoctrine()->getRepository(Libros::class)->find($ISBN);
        }
        if(!$ISBN){
            $respuesta['estado']=0;
            $respuesta['mensaje']="- El libro seleccionado no es válido o no existe";
            return new JsonResponse($respuesta);
        }
        $entityManager->remove($libro);
        $entityManager->flush();
        $respuesta['libros']=$this->buscarLibrosAction(1);
        return new JsonResponse($respuesta);
    }

    /**
     * @Route("/buscarautores")
     */
    public function buscarAutoresAction($indicador=0)
    {
        $aAutores=$this->getDoctrine()->getRepository(Autores::class)->findAll();
        $autores=[];
        foreach($aAutores as $autor){
            $autores[]=[
                'id'=>$autor->getId(), 
                'nombre'=>$autor->getNombre(), 
                'apellidos'=>$autor->getApellidos()
            ];
        }
        if(!$indicador){
            return new JsonResponse(['autores'=>$autores]);
        }
        return $autores;
        
    }

    /**
     * @Route("/guardarautor")
     */
    public function guardarAutorAction(Request $request)
    {        
        $id=$request->get('id');
        $nombre=$request->get('nombre');
        $apellidos=$request->get('apellidos');
        $entityManager = $this->getDoctrine()->getManager();
        if(!$id){
            $autor = new Autores();            
        }else{
            $autor = $this->getDoctrine()->getRepository(Autores::class)->find($id);
        }
        $autor->setNombre($nombre);
        $autor->setApellidos($apellidos);
        $entityManager->persist($autor);
        $entityManager->flush();
        return new JsonResponse(['autores'=>$this->buscarAutoresAction(1)]);
    }

    /**
     * @Route("/eliminarautor")
     */
    public function eliminarAutorAction(Request $request)
    {        
        $id=$request->get('id');
        $entityManager = $this->getDoctrine()->getManager();
        $respuesta=[
            'estado'=>1
        ];
        $autor=null;
        if($id){
            $autor = $this->getDoctrine()->getRepository(Autores::class)->find($id);
        }
        if(!$autor){
            $respuesta['estado']=0;
            $respuesta['mensaje']="- El autor seleccionado no es válido o no existe";
            return new JsonResponse($respuesta);
        }
        $entityManager->remove($autor);
        $entityManager->flush();
        $respuesta['autores']=$this->buscarAutoresAction(1);
        return new JsonResponse($respuesta);
    }

    /**
     * @Route("/buscareditorial")
     */
    public function buscarEditorialesAction($indicador=0)
    {
        $aEditoriales=$this->getDoctrine()->getRepository(Editoriales::class)->findAll();
        $editoriales=[];
        foreach($aEditoriales as $editor){
            $editoriales[]=[
                'id'=>$editor->getId(), 
                'nombre'=>$editor->getNombre(), 
                'sede'=>$editor->getSede()
            ];
        }
        if(!$indicador){
            return new JsonResponse(['editoriales'=>$editoriales]);
        }
        return $editoriales;
    }

    /**
     * @Route("/guardareditorial")
     */
    public function guardarEditorialAction(Request $request)
    {        
        $id=$request->get('id');
        $nombre=$request->get('nombre');
        $sede=$request->get('sede');
        $entityManager = $this->getDoctrine()->getManager();
        if(!$id){
            $editor = new Editoriales();            
        }else{
            $editor = $this->getDoctrine()->getRepository(Editoriales::class)->find($id);
        }
        $editor->setNombre($nombre);
        $editor->setSede($sede);
        $entityManager->persist($editor);
        $entityManager->flush();
        return new JsonResponse(['editoriales'=>$this->buscarEditorialesAction(1)]);
    }

    /**
     * @Route("/eliminareditorial")
     */
    public function eliminarEditorialAction(Request $request)
    {        
        $id=$request->get('id');
        $entityManager = $this->getDoctrine()->getManager();
        $respuesta=[
            'estado'=>1
        ];
        $editor=null;
        if($id){
            $editor = $this->getDoctrine()->getRepository(Editoriales::class)->find($id);
        }
        if(!$editor){
            $respuesta['estado']=0;
            $respuesta['mensaje']="- El editor seleccionado no es válido o no existe";
            return new JsonResponse($respuesta);
        }
        $entityManager->remove($editor);
        $entityManager->flush();
        $respuesta['editoriales']=$this->buscarEditorialesAction(1);
        return new JsonResponse($respuesta);
    }
}