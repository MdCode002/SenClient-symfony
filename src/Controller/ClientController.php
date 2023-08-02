<?php

namespace App\Controller;

use Dompdf\Dompdf;
use App\Entity\Client;
use App\Form\FiltreType;
use App\Form\SearchType;
use App\Model\SearchData;
use Dompdf\Options;
use App\Form\ClientFormType;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ClientController extends AbstractController
{

    /**
     * Permet d'afficher la liste des Clients
     *
     * @param ClientRepository $repo
     * @param Request $request
     * @param PaginatorInterface $paginaotr
     * @return Response
     */
    #[Route('/', name: 'Home.client')]
    #[IsGranted("ROLE_USER")]
    public function index(ClientRepository $repo , Request $request , PaginatorInterface $paginaotr): Response
    {

        // Form pour rechercher
       $searchData = new SearchData();
       $form = $this->createForm(SearchType::class, $searchData);
       $form->handleRequest($request);

        // Form pour Filtre
       $filtreData = new  SearchData();
       $filtre = $this->createForm(FiltreType::class, $filtreData);
       $filtre->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
                $Client = $paginaotr->paginate(
                    $repo->findBySearch($searchData->recherche, $searchData->searchTerm),
                    $request->query->getInt('page', 1), /*page number*/
                    10);
                    return $this->render('client/index.html.twig', [
                        'clients' => $Client,'form' => $form->createView(),'filtre' => $filtre->createView()
                    ]);
        }

        $filtreData = new  SearchData();
        $filtre = $this->createForm(FiltreType::class, $filtreData);
        $filtre->handleRequest($request);
         if ($filtre->isSubmitted() && $filtre->isValid()){
                 $Client = $paginaotr->paginate(
                     $repo->findBySearch($filtreData->searchTerm, "statut"),
                     $request->query->getInt('page', 1), /*page number*/
                     10);
                     return $this->render('client/index.html.twig', [
                        'clients' => $Client,'form' => $form->createView(),'filtre' => $filtre->createView()
                     ]);
         }


        $Client = $paginaotr->paginate(
        $repo->findAll(),
        $request->query->getInt('page', 1), /*page number*/
        10 /*limit per page*/
        )
         ;
        // dd($Client);


        return $this->render('client/index.html.twig', [
            'clients' => $Client,'form' => $form->createView(),'filtre' => $filtre->createView()
        ]);
    }

    /**
     * Permet d'ajouter un nouveau client
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/Nouveau', name: 'new.client')]
    #[IsGranted("ROLE_USER")]
    public function new( Request $request , EntityManagerInterface $manager): Response
    {  
       $Client = new Client();
       $form = $this->createForm(ClientFormType::class, $Client);
       $form->handleRequest($request);
       if($form->isSubmitted() && $form->isValid()){
       $manager->persist($form->getData());
       $manager->flush();
       return $this->redirectToRoute('Home.client');
    }

        return $this->render('client/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    
    
    #[Route('/Suprimer/{id}', name: 'delete.client',methods: ['GET','POST'])]
    #[IsGranted("ROLE_USER")]
    public function Delete( ClientRepository $repo ,Request $request , EntityManagerInterface $manager ,int $id): Response
    {  
     $client = $repo->findOneBy(['id'=> $id]);
     
     if (!$client){
     return $this->redirectToRoute('Home.client');
    }else{
    $manager->remove($client);
     $manager->flush();
     return $this->redirectToRoute('Home.client');
}
    }



    
    
    #[Route('/Modifier/{id}', name: 'Updated.client',methods: ['GET','POST'])]
    #[IsGranted("ROLE_USER")]
    public function Updated( ClientRepository $repo ,Request $request , EntityManagerInterface $manager ,int $id): Response
    {  

     $Client = $repo->findOneBy(['id'=> $id]);
     $form = $this->createForm(ClientFormType::class, $Client);
     $form->handleRequest($request);
     if($form->isSubmitted() && $form->isValid()){
     $manager->persist($form->getData());
     $manager->flush();
     return $this->redirectToRoute('Home.client');
  }

      return $this->render('client/updated.html.twig', [
          'form' => $form->createView(),
      ]);
    }

    #[Route('/exporter', name: 'exporter.client')]
    #[IsGranted("ROLE_USER")]
    public function exporte(ClientRepository $repo , Request $request , PaginatorInterface $paginaotr): Response
    {

    $clients = $repo->findAll();


    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);
    $dompdf = new Dompdf($options);


    $html = '<html><body>';
    $html .= '<center><h1>Liste des clients</h1></center>';
    $html .= '<table border="1">';
    $html .= '<tr><th>Nom</th><th>Adresse</th><th>Téléphone</th><th>Email</th><th>Sexe</th><th>Statut</th></tr>';

    foreach ($clients as $client) {
        $statut = ($client->isStatut()) ? "actif" : "inactif";
        $html .= '<tr>';
        $html .= '<td>' . $client->getNom() . '</td>';
        $html .= '<td>' . $client->getAdresse() . '</td>';
        $html .= '<td>' . $client->getTel() . '</td>';
        $html .= '<td>' . $client->getEmail() . '</td>';
        $html .= '<td>' . $client->getSexe() . '</td>';
        $html .= '<td>' . $statut . '</td>';
        $html .= '</tr>';
    }

    $html .= '</table>';
    $html .= '</body></html>';

    $dompdf->loadHtml($html);

    $dompdf->setPaper('A4', 'portrait');

    $dompdf->render();

    $response = new Response($dompdf->output());

    $response->headers->set('Content-Type', 'application/pdf');
    $response->headers->set('Content-Disposition', 'attachment; filename="liste_clients.pdf"');

    return $response;
}

        
    }


