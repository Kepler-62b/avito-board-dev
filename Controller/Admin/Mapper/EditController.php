<?php
/*
 *  Copyright 2023.  Baks.dev <admin@baks.dev>
 *
 *  Permission is hereby granted, free of charge, to any person obtaining a copy
 *  of this software and associated documentation files (the "Software"), to deal
 *  in the Software without restriction, including without limitation the rights
 *  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 *  copies of the Software, and to permit persons to whom the Software is furnished
 *  to do so, subject to the following conditions:
 *
 *  The above copyright notice and this permission notice shall be included in all
 *  copies or substantial portions of the Software.
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *  FITNESS FOR A PARTICULAR PURPOSE AND NON INFRINGEMENT. IN NO EVENT SHALL THE
 *  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 *  THE SOFTWARE.
 */

namespace BaksDev\Avito\Board\Controller\Admin\Mapper;

use BaksDev\Avito\Board\Entity\Event\AvitoBoardEvent;
use BaksDev\Avito\Board\UseCase\Mapper\NewEdit\MapperDTO;
use BaksDev\Avito\Board\UseCase\Mapper\NewEdit\MapperForm;
use BaksDev\Avito\Board\UseCase\Mapper\NewEdit\MapperHandler;
use BaksDev\Core\Controller\AbstractController;
use BaksDev\Core\Listeners\Event\Security\RoleSecurity;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
#[RoleSecurity('ROLE_AVITO_BOARD_MAPPER_EDIT')]
final class EditController extends AbstractController
{
    #[Route('/admin/avito-board/mapper/edit/{id}', name: 'admin.mapper.edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, #[MapEntity] AvitoBoardEvent $event, MapperHandler $handler): Response
    {
        $mapperDTO = new MapperDTO();

        $event->getDto($mapperDTO);

        $form = $this->createForm(MapperForm::class, $mapperDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $form->has('mapper_new'))
        {
            $this->refreshTokenForm($form);

            $result = $handler->handle($mapperDTO);

            if ($result)
            {
                $this->addFlash('page.edit', 'success.edit', 'avito-board.admin');

                return $this->redirectToRoute('avito-board:admin.mapper.index');
            }

            $this->addFlash('page.edit', 'danger.update', 'avito-board.admin', $result);

            return $this->redirectToReferer();
        }

        return $this->render(['form' => $form->createView()]);

    }
}
