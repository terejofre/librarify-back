<?php

namespace App\Service\Category;

use App\Entity\Category;
use App\Event\Form\Model\CategoryDto;
use App\Event\Form\Type\CategoryFormType;
use App\Repository\CategoryRepository;
use App\Service\Utils\Security;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class CategoryFormProcessor
{
    public function __construct(
        private GetCategory $getCategory,
        private CategoryRepository $categoryRepository,
        private FormFactoryInterface $formFactory,
        private Security $security
    ) {
    }

    public function __invoke(Request $request, ?string $categoryId = null): array
    {
        $category = null;
        $categoryDto = null;

        if ($categoryId === null) {
            $categoryDto = new CategoryDto();
        } else {
            $category = ($this->getCategory)($categoryId);
            $categoryDto = CategoryDto::createFromCategory($category);
        }

        $form = $this->formFactory->create(CategoryFormType::class, $categoryDto);
        $content = json_decode($request->getContent(), true);
        $form->submit($content);
        if (!$form->isSubmitted()) {
            return [null, 'Form is not submitted'];
        }
        if (!$form->isValid()) {
            return [null, $form];
        }

        if ($category === null) {
            $user = $this->security->getCurrentUser();
            $category = Category::create(
                $categoryDto->name,
                $user
            );
        } else {
            $category->update(
                $categoryDto->name
            );
        }

        $this->categoryRepository->save($category);
        return [$category, null];
    }
}
