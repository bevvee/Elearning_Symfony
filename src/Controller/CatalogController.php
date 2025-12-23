<?php

declare(strict_types=1);

namespace App\Controller;

use App\Course\Handler\DefaultCourseHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/catalog', name: 'app_catalog_')]
class CatalogController extends AbstractController
{
    public function __construct(private readonly DefaultCourseHandler $courseHandler)
    {
    }

    #[Route(path: '/{slug}', name: 'view')]
    public function show(string $slug): Response
    {
        $course = $this->courseHandler->getCourseBySlug($slug); // simulate loading this course from the storge (from API or Database)

        if (null === $course) {
            throw $this->createNotFoundException('La page que vous demandez est introuvable.');
        }

        return $this->render('catalog/show.html.twig', [
            'course' => $course,
        ]);
    }

    #[Route(path: '/all', name: 'all', priority: 1)]
    public function all(): Response
    {
        $courses = $this->courseHandler->fetchAllCourses(); // simulate loading this course from the storge (from API or Database)

        return $this->render('catalog/index.html.twig', [
            'courses' => $courses,
        ]);
    }

    public function similarCourses(int $limit = 2): Response
    {
        $similarCourses = $this->courseHandler->findSimilarCourses($limit);

        return $this->render('catalog/similar_courses.html.twig', [
            'courses' => $similarCourses,
        ]);
    }
}
