<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\TotalMovie;
use App\Form\Type\MovieType;
use App\Repository\CategoryRepository;
use App\Repository\MovieRepository;
use App\Repository\TotalMovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Class MovieController
 */
#[Route(path: ['fr' => '/film', 'en' => '/movie'], name:'movie_')]
class MovieController extends AbstractController
{
    /**
     * @param MailerInterface    $mailer
     * @param MovieRepository    $movieRepository
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(protected MailerInterface $mailer, private readonly MovieRepository $movieRepository, private readonly CategoryRepository $categoryRepository)
    {
    }

    /**
     * @param Movie|null           $movie
     * @param TotalMovieRepository $totalMovieRepository
     *
     * @return Response
     */
    #[Route(path: ['fr' => '/voir/{id}', 'en' => '/show{id}'], name: 'show')]
    public function show(?Movie $movie, TotalMovieRepository $totalMovieRepository): Response
    {
        return $this->render('movie-show.html.twig', [
            'movie' => $movie,
            'categories' => $this->categoryRepository->findAll(),
            'nbMovie' => $totalMovieRepository->findOneBy(['id' => 1]),
        ]);
    }

    /**
     * @param Request              $request
     * @param SluggerInterface     $slugger
     * @param TotalMovieRepository $totalMovieRepository
     *
     * @return Response
     *
     * @throws TransportExceptionInterface
     */
    #[Route(path: ['fr' => '/ajouter', 'en' => '/add'], name: 'add')]
    public function add(Request $request, SluggerInterface $slugger, TotalMovieRepository $totalMovieRepository): Response
    {
        $movie = new Movie();
        $form = $this->createForm(MovieType::class, $movie)->handleRequest($request);

        $nbMovie = $totalMovieRepository->findOneBy(['id' => 1]);
        if (null === $nbMovie) {
            $nbMovie = new TotalMovie();
            $nbMovie->setNbMovie(0);
            $totalMovieRepository->add($nbMovie, true);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $posterFile */
            $posterFile = $form->get('poster')->getData();

            if ($posterFile) {
                $originalFilename = pathinfo($posterFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid('', true).'.'.$posterFile->guessExtension();

                try {
                    $posterFile->move(
                        $this->getParameter('poster_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    dd($e);
                }

                $movie->setPoster($newFilename);
            }

            $this->movieRepository->add($movie, true);
            $nbMovie->setNbMovie($nbMovie->getNbMovie() + 1);
            $totalMovieRepository->add($nbMovie, true);


            $email = (new Email())
                ->from('admin@videotheque.fr')
                ->to('anaelle.garnnier@bimeo.fr')
                ->subject('Time for Symfony Mailer!')
                ->text('Sending emails is fun again!')
                ->html('<p>Liste de film modifié</p>');

            $this->mailer->send($email);

            return $this->redirectToRoute('movie_show', ['id' => $movie->getId()]);
        }

        return $this->render('movie-add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Movie|null       $movie
     * @param Request          $request
     * @param SluggerInterface $slugger
     *
     * @return Response
     */
    #[Route(path: ['fr' => '/modifier-film/{id}', 'en' => '/edit-movie/{id}'], name: 'edit')]
    public function edit(?Movie $movie, Request $request, SluggerInterface $slugger): Response
    {
        if (null === $movie) {
            return $this->redirectToRoute('category_index');
        }

        $form = $this->createForm(MovieType::class, $movie)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                /** @var UploadedFile $posterFile */
                $posterFile = $form->get('poster')->getData();
                if (null !== $posterFile) {
                    $originalFilename = pathinfo($posterFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid('', true).'.'.$posterFile->guessExtension();

                    $posterFile->move(
                        $this->getParameter('poster_directory'),
                        $newFilename
                    );

                    $movie->setPoster($newFilename);
                }

                $this->movieRepository->add($movie, true);
            } catch (\Exception|FileException $exception) {
                dd($exception);
            }

            return $this->redirectToRoute('movie_show', ['id' => $movie->getId()]);
        }

        return $this->render('edit-movie.html.twig', [
            'form' => $form,
            'movie' => $movie,
            'categories' => $this->categoryRepository->findAll(),
        ]);
    }

    /**
     * @param Movie|null           $movie
     * @param TotalMovieRepository $totalMovieRepository
     *
     * @return Response
     *
     * @throws TransportExceptionInterface
     */
    #[Route(path: ['fr' => '/suppression-film/{id}', 'en' => '/delete-movie/{id}'], name: 'delete')]
    public function delete(?Movie $movie, TotalMovieRepository $totalMovieRepository):Response
    {
        if (null === $movie) {
            return $this->redirectToRoute('category_index');
        }

        $nbMovie = $totalMovieRepository->findOneBy(['id' => 1]);
        if (null === $nbMovie) {
            $nbMovie = new TotalMovie();
            $nbMovie->setNbMovie(0);
            $totalMovieRepository->add($nbMovie, true);
        }

        try {
            $this->movieRepository->remove($movie, true);
            if (0 !== $nbMovie->getNbMovie()) {
                $nbMovie->setNbMovie($nbMovie->getNbMovie() - 1);
                $totalMovieRepository->add($nbMovie, true);
            }

            $email = (new Email())
                ->from('admin@videotheque.fr')
                ->to('anaelle.garnnier@bimeo.fr')
                ->subject('Time for Symfony Mailer!')
                ->text('Sending emails is fun again!')
                ->html('<p>Liste de film modifié</p>');

            $this->mailer->send($email);
        } catch (\Exception $exception) {
            dd($exception);
        }

        return $this->redirectToRoute('category_index');
    }
}
