<?php

namespace App\Controller;

use App\Entity\Student;
use App\Form\StudentType;
use App\Repository\StudentRepository;
use App\Service\Api;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/student")
 */
class StudentController extends AbstractController
{
    /**
     * @Route("/", name="student_index", methods={"GET","POST"})
     */
    public function index(Request $request, StudentRepository $studentRepository, Api $api): Response
    {
        $student = new Student();
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $student->setUserId($this->getUser());
            $locate = $api->localize($form->get('city')->getData());
            if (empty($locate)) {
               $locate[0]['lat'] = 0;
               $locate[0]['lon'] = 0;
            }
            $student->setLatitude($locate[0]['lat']);
            $student->setLongitude($locate[0]['lon']);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($student);
            $entityManager->flush();
            if($locate[0]['lat'] === 0 & $locate[0]['lon'] === 0) {
                $this->addFlash('danger','Ville inconnue');
            }
            return $this->redirectToRoute('student_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('student/index.html.twig', [
            'students' => $studentRepository->findBy(['userId'=>$this->getUser()]),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}", name="student_show", methods={"GET"})
     */
    public function show(Student $student): Response
    {
        return $this->render('student/show.html.twig', [
            'student' => $student,
        ]);
    }

    /**
     * @Route("/{id}", name="student_delete", methods={"POST"})
     */
    public function delete(Request $request, Student $student): Response
    {
        if ($this->isCsrfTokenValid('delete' . $student->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($student);
            $entityManager->flush();
        }

        return $this->redirectToRoute('student_index', [], Response::HTTP_SEE_OTHER);
    }
}
