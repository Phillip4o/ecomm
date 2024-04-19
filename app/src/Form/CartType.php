<?php

namespace App\Form;

use App\Entity\Order;
use App\Enum\DeliveryMethod;
use App\Enum\PaymentMethod;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CartType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('items', CollectionType::class, [
                'entry_type' => CartItemType::class
            ])
            ->add('customerName')
            ->add('customerEmail')
            ->add('customerCity')
            ->add('customerAddress')
            ->add('paymentMethod', EnumType::class, [
                    'class' => PaymentMethod::class,
            ])
            ->add('deliveryMethod', EnumType::class, [
                    'class' => DeliveryMethod::class,
            ])
            ->add('save', SubmitType::class)
            ->add('clear', SubmitType::class)
            ->addEventListener(
                FormEvents::POST_SUBMIT,
                [$this, 'postSubmit']
        );
    }

    #[NoReturn] public function postSubmit(PostSubmitEvent $event): void
    {
        $form = $event->getForm();
        $cart = $form->getData();

        if (!$cart instanceof Order) {
            return;
        }

        foreach ($form->get('items')->all() as $child) {
            if ($child->get('remove')->isClicked()) {
                $cart->removeItem($child->getData());
                break;
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
