<?php

declare(strict_types=1);

namespace Alengo\SuluContactAccountExtraBundle\Admin;

use Sulu\Bundle\AdminBundle\Admin\Admin;
use Sulu\Bundle\AdminBundle\Admin\View\ToolbarAction;
use Sulu\Bundle\AdminBundle\Admin\View\ViewBuilderFactoryInterface;
use Sulu\Bundle\AdminBundle\Admin\View\ViewCollection;
use Sulu\Bundle\ContactBundle\Admin\ContactAdmin;

class ContactAccountExtraAdmin extends Admin
{
    public function __construct(
        private readonly ViewBuilderFactoryInterface $viewBuilderFactory,
        private readonly string $contactFormKey,
        private readonly string $contactResourceKey,
        private readonly string $contactTabTitle,
        private readonly string $accountFormKey,
        private readonly string $accountResourceKey,
        private readonly string $accountTabTitle,
    ) {
    }

    public function configureViews(ViewCollection $viewCollection): void
    {
        if ($viewCollection->has('sulu_contact.contact_edit_form.details')) {
            $tabOrder = $viewCollection->get('sulu_contact.contact_edit_form.details')->getView()->getOption('tabOrder') + 1;

            $viewCollection->add(
                $this->viewBuilderFactory
                    ->createFormViewBuilder('alengo_contact_extra.contact_additional_data', '/additional-data')
                    ->setResourceKey($this->contactResourceKey)
                    ->setFormKey($this->contactFormKey)
                    ->setTabTitle($this->contactTabTitle)
                    ->addToolbarActions([new ToolbarAction('sulu_admin.save')])
                    ->setTabOrder($tabOrder)
                    ->setParent(ContactAdmin::CONTACT_EDIT_FORM_VIEW),
            );
        }

        if ($viewCollection->has('sulu_contact.account_edit_form.details')) {
            $tabOrder = $viewCollection->get('sulu_contact.account_edit_form.details')->getView()->getOption('tabOrder') + 1;

            $viewCollection->add(
                $this->viewBuilderFactory
                    ->createFormViewBuilder('alengo_contact_extra.account_additional_data', '/additional-data')
                    ->setResourceKey($this->accountResourceKey)
                    ->setFormKey($this->accountFormKey)
                    ->setTabTitle($this->accountTabTitle)
                    ->addToolbarActions([new ToolbarAction('sulu_admin.save')])
                    ->setTabOrder($tabOrder)
                    ->setParent(ContactAdmin::ACCOUNT_EDIT_FORM_VIEW),
            );
        }
    }

    public static function getPriority(): int
    {
        return ContactAdmin::getPriority() - 1;
    }
}
