# SuluContactAccountExtraBundle

Adds a configurable **Additional Data** tab to [Sulu CMS](https://sulu.io/) 3.x Contacts and Accounts. Custom fields are stored as a JSON column on the entity — no extra tables, no extra API routes beyond the two provided endpoints.

## Features

- **Additional Data tab** — auto-registered for Contacts and Accounts
- **Built-in entities** — concrete `Contact` and `Account` extending Sulu's base classes with a JSON `additionalData` field; no project entities required
- **Generic REST API** — GET/PUT endpoints serialize the full JSON blob; the project form defines the fields
- **Zero-config entity registration** — `sulu_contact` objects and `sulu_admin` resources are auto-configured via `PrependExtensionInterface`

## Requirements

- PHP 8.2+
- Sulu CMS ~3.0
- Symfony 7.x

## Installation

```bash
composer require alengo/sulu-contact-account-extra-bundle
```

Register the bundle in `config/bundles.php`:

```php
Alengo\SuluContactAccountExtraBundle\AlengoContactAccountExtraBundle::class => ['all' => true],
```

Import the admin API routes in `config/routes/sulu_admin.yaml`:

```yaml
ContactAccountExtraBundle:
    resource: "@AlengoContactAccountExtraBundle/Resources/config/routing_admin_api.yaml"
    prefix: /admin/api
```

Run a database migration to add the `additionalData` JSON column:

```bash
bin/adminconsole doctrine:migrations:diff
bin/adminconsole doctrine:migrations:migrate
```

That's it — no further configuration required for a standard setup.

## Configuration

All configuration is optional. Defaults work out of the box.

```yaml
alengo_contact_account_extra:
    contact:
        form_key: contact_additional_data       # default
        resource_key: contact_additional_data   # default
        tab_title: sulu_admin.app.additional_data
    account:
        form_key: account_additional_data       # default
        resource_key: account_additional_data   # default
        tab_title: sulu_admin.app.additional_data
```

To use custom entity classes:

```yaml
alengo_contact_account_extra:
    contact:
        entity_class: App\Entity\Contact
    account:
        entity_class: App\Entity\Account
```

## Provided Forms

The bundle does **not** ship default form XML files — the project controls field definitions. Create your own in `config/forms/`:

```
config/forms/contact_additional_data.xml
config/forms/account_additional_data.xml
```

Example form:

```xml
<?xml version="1.0" ?>
<form xmlns="http://schemas.sulu.io/template/template"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://schemas.sulu.io/template/template https://github.com/sulu/sulu/blob/2.x/src/Sulu/Bundle/AdminBundle/Resources/schema/form.xsd">

    <key>contact_additional_data</key>

    <properties>
        <property name="externalId" type="text_line" mandatory="false">
            <meta>
                <title lang="de">Externe ID</title>
                <title lang="en">External ID</title>
            </meta>
        </property>
    </properties>
</form>
```

## API Endpoints

| Method | Path | Description |
|---|---|---|
| `GET` | `/admin/api/account-additional-data/{id}` | Load account additional data |
| `PUT` | `/admin/api/account-additional-data/{id}` | Save account additional data |
| `GET` | `/admin/api/contact-additional-data/{id}` | Load contact additional data |
| `PUT` | `/admin/api/contact-additional-data/{id}` | Save contact additional data |

## Provided Models

| Class | Purpose |
|---|---|
| `Entity\Account` | Concrete Doctrine entity (`co_accounts`) with `additionalData` JSON column |
| `Entity\Contact` | Concrete Doctrine entity (`co_contacts`) with `additionalData` JSON column |
