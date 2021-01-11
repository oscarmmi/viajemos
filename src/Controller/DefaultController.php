<?php
namespace App\Controller;
use App\Entity\Autores;
use App\Entity\Editoriales;
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
        return $this->render('base.html.twig');
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