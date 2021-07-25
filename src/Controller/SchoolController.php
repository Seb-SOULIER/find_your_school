<?php

namespace App\Controller;

use App\Entity\Student;
use App\Service\Api;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SchoolController extends AbstractController
{
    /**
     * @Route("/school/list/{id}", name="school_list", methods={"GET"})
     */
    public function index(Api $api, Student $student): Response
    {
        return $this->render('school/index.html.twig', [
            'student' => $student,
            'schools' => $api->searchSchool($student),
        ]);
    }

    /**
     * @Route("/school/{id}/show/{school}", name="school_show")
     */
    public function show(Api $api, Student $student, Request $request): Response
    {
        $school=[];
        $schools = $api->searchSchool($student);
        foreach ($schools['records'] as $schol) {
            if ($schol['fields']['identifiant_de_l_etablissement'] === $request->get('school')) {
                $school = $schol;
            }
        }

        if ($this->isCsrfTokenValid('school'.$student->getId(), $request->request->get('_token'))) {
            $student->setSchoolName($school['fields']['nom_etablissement']);
            $student->setSchoolId($school['fields']['identifiant_de_l_etablissement']);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            $this->addFlash('success',
                $student->getSchoolName()
                . ' ajouter à ' . $student->getLastname()
                . " " . $student->getFirstname());
            return $this->redirectToRoute('student_index');
        }

        return $this->render('school/show.html.twig', [
            'student' => $student,
            'schools' => $schools,
            'school' => $school
        ]);
    }
}
