<?php

namespace App\Controller;

use App\Entity\Course;
use App\Form\CourseType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{RedirectResponse, Request, Response};

class CourseController extends AbstractController
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {}

    #[Route('/', name: 'app.course.index')]
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $courses = $this->entityManager->getRepository(Course::class);

        $pagination = $paginator->paginate(
            $courses->findAll(),
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('course/index.html.twig', [
            'courses' => $pagination
        ]);
    }

    #[Route('/new', name: 'app.course.new')]
    public function new(Request $request): Response|RedirectResponse
    {
        $form = $this->createForm(CourseType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $course = $form->getData();

            $this->entityManager->persist($course);
            $this->entityManager->flush();

            return $this->redirectToRoute('app.course.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('course/_form.html.twig', [
            'form' => $form->createView(),
            'is_editing' => false
        ]);
    }

    #[Route('/course/{id}', name: 'app.course.show')]
    public function show(int $id): Response
    {
        $courses = $this->entityManager->getRepository(Course::class);

        return $this->render('course/show.html.twig', [
            'course' => $courses->find($id)
        ]);
    }

    #[Route('/course/{id}/delete', name: 'app.course.delete')]
    public function delete(int $id): RedirectResponse
    {
        $course = $this->entityManager->find(Course::class, $id);

        $this->entityManager->remove($course);
        $this->entityManager->flush();

        return $this->redirectToRoute('app.course.index', [], Response::HTTP_SEE_OTHER);
    }
}
