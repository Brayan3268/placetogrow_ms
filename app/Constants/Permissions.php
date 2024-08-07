<?php

namespace App\Constants;

class Permissions
{
    public const SUPER_USER_OPTIONS = 'super_user.options';

    public const SUPER_USERS_CREATE = 'super_users.create';

    public const SUPER_USERS_EDIT = 'super_users.edit';

    public const SUPER_USERS_SHOW = 'super_users.show';

    public const SUPER_USERS_DESTROY = 'super_users.destroy';

    public const USERS_INDEX = 'users.index';

    public const USERS_MENU_SHOW = 'users_menu.show';

    public const USERS_CREATE = 'users.create';

    public const USERS_STORE = 'users.store';

    public const USERS_EDIT = 'users.edit';

    public const USERS_SHOW = 'users.show';

    public const USERS_DESTROY = 'users.destroy';

    public const SITES_INDEX = 'sites.index';

    public const SITES_CREATE = 'sites.create';

    public const SITES_STORE = 'sites.store';

    public const SITES_EDIT = 'sites.edit';

    public const SITES_SHOW = 'sites.show';

    public const SITES_DESTROY = 'sites.destroy';

    public const SITES_MANAGE = 'site.manage';

    public const SITES_PAY = 'site.pay';

    public const PAYMENTS_SEE_ADMINS_USERS = 'payments.see_admins_users';

    public const PAY_INVOICES_SEE_ADMINS_USERS = 'pay_invoices.see_admins_users';

    public const INVOICES_SEE_ADMINS_USERS = 'invoices.see_admins_users';

    public static function get_all_permissions(): array
    {
        return (new \ReflectionClass(self::class))->getConstants();
    }

    public static function get_permissions_super_admin(): array
    {
        return [
            self::SUPER_USER_OPTIONS,
            self::USERS_INDEX,
            self::USERS_STORE,
            self::USERS_CREATE,
            self::USERS_DESTROY,
            self::USERS_EDIT,
            self::USERS_MENU_SHOW,
            self::USERS_SHOW,
            self::SITES_INDEX,
            self::SITES_STORE,
            self::SITES_CREATE,
            self::SITES_DESTROY,
            self::SITES_EDIT,
            self::SITES_SHOW,
            self::SITES_MANAGE,
            self::PAYMENTS_SEE_ADMINS_USERS,
            self::PAY_INVOICES_SEE_ADMINS_USERS,
            self::INVOICES_SEE_ADMINS_USERS,
        ];
    }

    public static function get_permissions_admin(): array
    {
        return [
            self::USERS_INDEX,
            self::USERS_STORE,
            self::USERS_CREATE,
            self::USERS_DESTROY,
            self::USERS_EDIT,
            self::USERS_MENU_SHOW,
            self::USERS_SHOW,
            self::SITES_INDEX,
            self::SITES_STORE,
            self::SITES_CREATE,
            self::SITES_DESTROY,
            self::SITES_EDIT,
            self::SITES_SHOW,
            self::SITES_MANAGE,
            self::PAYMENTS_SEE_ADMINS_USERS,
            self::PAY_INVOICES_SEE_ADMINS_USERS,
            self::INVOICES_SEE_ADMINS_USERS,
        ];
    }

    public static function get_permissions_guest(): array
    {
        return [
            self::USERS_SHOW,
            self::SITES_INDEX,
            self::SITES_SHOW,
            self::SITES_PAY,
        ];
    }
}
