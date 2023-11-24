<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Movie;
use App\Form\Type\CategoryType;
use App\Form\Type\SearchType;
use App\Repository\CategoryRepository;
use App\Repository\MovieRepository;
use App\Repository\TotalMovieRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CategoryController
 */
#[Route(path: ['fr' => '/categorie', 'en' => '/category_'], name:'category_')]
class CategoryController extends AbstractController
{
    /**
     * @param                    $paginateMaxPerPage
     * @param CategoryRepository $categoryRepository
     * @param MovieRepository    $movieRepository
     */
    public function __construct(protected $paginateMaxPerPage, private readonly CategoryRepository $categoryRepository, private readonly MovieRepository $movieRepository)
    {
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    #[Route( name: 'index')]
    public function index(Request $request, TotalMovieRepository $totalMovieRepository): Response
    {
        $category = new Category();
        $formCategory = $this->createForm(CategoryType::class, $category)->handleRequest($request);
        if ($formCategory->isSubmitted() && $formCategory->isValid()) {
            $this->categoryRepository->add($category, true);

            return $this->redirectToRoute('category_index');
        }

        $search = $searchDate = null;
        $searchForm = $this->createForm(SearchType::class)->handleRequest($request);
        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $search = $searchForm->getData()['search'];
            $searchDate = $searchForm->getData()['searchDate'];
        }

        $queryBuilder = $this->movieRepository->getAllQb();
        $movies = $this->movieRepository->findAll();
        if (null !== $search) {
            $queryBuilder->leftJoin('m.category', 'c');
            $queryBuilder->where('m.title LIKE :title');
            $queryBuilder->orWhere('c.name LIKE :title');
            $queryBuilder->setParameter('title', "%$search%");
            $movies = $queryBuilder->getQuery()->getResult();
        }

        if (null !== $searchDate) {
            $queryBuilder->where('m.createdAt = :createdAt');
            $queryBuilder->setParameter('createdAt', $searchDate);
            $movies = $queryBuilder->getQuery()->getResult();
        }

        return $this->render('index.html.twig', [
            'categories' => $this->categoryRepository->findAll(),
            'form' => $formCategory,
            'movies' => $movies,
            'searchForm' => $searchForm->createView(),
            'nbMovie' => $totalMovieRepository->findOneBy(['id' => 1]),
            ]);
    }

    /**
     * @param Request            $request
     * @param Category|null      $category
     * @param PaginatorInterface $paginator
     *
     * @return Response
     */
    #[Route(path: ['fr' => '/voir-categorie/{id}', 'en' => '/show-category/{id}'], name: 'show')]
    public function showCategory(Request $request, ?Category $category, PaginatorInterface $paginator): Response
    {
        if (null === $category) {
            return $this->redirectToRoute('index.html.twig');
        }

        $movies = $category->getMovies();

        $pagination = $paginator->paginate(
            $this->movieRepository->findAllInCategory($category, true),
            $request->query->getInt('page', 1),
            $this->paginateMaxPerPage
        );

        return $this->render('category-show.html.twig', [
            'pagination' => $pagination,
            'movies' => $movies,
            'category' => $category,
            'categories' => $this->categoryRepository->findAll(),
        ]);
    }

    /**
     * @param Category|null      $category
     * @param Request            $request
     * @param CategoryRepository $categoryRepository
     *
     * @return Response
     */
    #[Route(path: ['fr' => '/modifier-categorie/{id}', 'en' => '/edit-category/{id}'], name: 'edit')]
    public function editCategory(?Category $category, Request $request, CategoryRepository $categoryRepository): Response
    {
        if (null === $category) {
            return $this->redirectToRoute('category_index');
        }

        $formCategory = $this->createForm(CategoryType::class, $category)->handleRequest($request);
        if ($formCategory->isSubmitted() && $formCategory->isValid()) {
            $categoryRepository->add($category, true);

            return $this->redirectToRoute('category_index');
        }
        return $this->render('edit-category.html.twig', [
            'categories'=> $this->categoryRepository->findAll(),
            'form' => $formCategory,
        ]);
    }

    /**
     * @param Category|null      $category
     * @param CategoryRepository $categoryRepository
     *
     * @return Response
     */
    #[Route(path: ['fr' => '/suppression-categorie/{id}', 'en' => '/delete-category/{id}'], name: 'delete')]
    public function deleteCategory(?Category $category, CategoryRepository $categoryRepository):Response
    {
        if (null === $category) {
            return $this->redirectToRoute('category_index');
        }

        try {
            $categoryRepository->remove($category, true);
        } catch (\Exception $exception) {
            dd($exception);
        }

        return $this->redirectToRoute('category_index');
    }
}
