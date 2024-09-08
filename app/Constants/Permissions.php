<?php

namespace App\Constants;

class Permissions
{
    public const SUPER_USER_OPTIONS = 'super_user.options';

    public const SUPER_USERS_CREATE = 'super_users.create';

    public const SUPER_USERS_EDIT = 'super_users.edit';

    public const SUPER_USERS_SHOW = 'super_users.show';

    public const SUPER_USERS_DESTROY = 'super_users.destroy';

    public const ADMIN_USERS_SHOW = 'admin_user.show';

    public const USERS_INDEX = 'users.index';

    public const USERS_STORE = 'users.store';

    public const USERS_CREATE = 'users.create';

    public const USERS_DESTROY = 'users.destroy';

    public const USERS_EDIT = 'users.edit';

    public const USERS_SHOW = 'users.show';

    public const USERS_MENU_SHOW = 'users_menu.show';

    public const SITES_INDEX = 'sites.index';

    public const SITES_STORE = 'sites.store';

    public const SITES_CREATE = 'sites.create';

    public const SITES_DESTROY = 'sites.destroy';

    public const SITES_EDIT = 'sites.edit';

    public const SITES_SHOW = 'sites.show';

    public const SITES_MANAGE = 'site.manage';

    public const SITES_PAY = 'site.pay';

    public const PAYMENTS_SEE_ADMINS_USERS = 'payments.see_admins_users';

    public const PAY_INVOICES_SEE_ADMINS_USERS = 'pay_invoices.see_admins_users';

    public const PAYS_INFO_SHOW = 'pays_info.show';

    public const INVOICES_INDEX = 'invoices.index';

    public const INVOICES_STORE = 'invoices.store';

    public const INVOICES_CREATE = 'invoices.create';

    public const INVOICES_DESTROY = 'invoices.destroy';

    public const INVOICES_EDIT = 'invoices.edit';

    public const INVOICES_SHOW = 'invoices.show';

    public const INVOICES_SEE_ADMINS_USERS = 'invoices.see_admins_users';

    public const INVOICES_PAY = 'pay_invoices.see_user';

    public const INVOICES_INFO_SHOW = 'invoices_info.show';

    public const SUSCRIPTION_INDEX = 'suscription.index';

    public const SUSCRIPTION_STORE = 'suscription.store';

    public const SUSCRIPTION_CREATE = 'suscription.create';

    public const SUSCRIPTION_DESTROY = 'suscription.destroy';

    public const SUSCRIPTION_EDIT = 'suscription.edit';

    public const SUSCRIPTION_SHOW = 'suscription.show';

    public const SUSCRIPTION_MANAGE = 'suscription.manage';

    public const SUSCRIPTIONS_SPECIFIC_USER = 'suscriptions_users.index';

    public const USER_GET_SUSCRIPTION = 'suscriptions.user_get_suscription';

    public const USER_SUSCRIPTION_STORE = 'user_suscriptions.store';

    public const USER_SUSCRIPTION_SHOW = 'user_suscriptions.show';

    public static function get_all_permissions(): array
    {
        return (new \ReflectionClass(self::class))->getConstants();
    }

    public static function get_permissions_super_admin(): array
    {
        return [
            self::SUPER_USER_OPTIONS,
            self::SUPER_USERS_SHOW,
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
            self::PAYS_INFO_SHOW,
            self::INVOICES_INDEX,
            self::INVOICES_STORE,
            self::INVOICES_CREATE,
            self::INVOICES_DESTROY,
            self::INVOICES_EDIT,
            self::INVOICES_SHOW,
            self::INVOICES_SEE_ADMINS_USERS,
            self::INVOICES_INFO_SHOW,
            self::SITES_PAY,
            self::SUSCRIPTION_INDEX,
            self::SUSCRIPTION_STORE,
            self::SUSCRIPTION_CREATE,
            self::SUSCRIPTION_DESTROY,
            self::SUSCRIPTION_EDIT,
            self::SUSCRIPTION_SHOW,
            self::SUSCRIPTION_MANAGE,
        ];
    }

    public static function get_permissions_admin(): array
    {
        return [
            self::ADMIN_USERS_SHOW,
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
            self::PAYS_INFO_SHOW,
            self::INVOICES_INDEX,
            self::INVOICES_STORE,
            self::INVOICES_CREATE,
            self::INVOICES_DESTROY,
            self::INVOICES_EDIT,
            self::INVOICES_SHOW,
            self::INVOICES_SEE_ADMINS_USERS,
            self::INVOICES_INFO_SHOW,
            self::SITES_PAY,
            self::SUSCRIPTION_INDEX,
            self::SUSCRIPTION_STORE,
            self::SUSCRIPTION_CREATE,
            self::SUSCRIPTION_DESTROY,
            self::SUSCRIPTION_EDIT,
            self::SUSCRIPTION_SHOW,
            self::SUSCRIPTION_MANAGE,
        ];
    }

    public static function get_permissions_guest(): array
    {
        return [
            self::USERS_SHOW,
            self::SITES_INDEX,
            self::SITES_SHOW,
            self::SITES_PAY,
            self::INVOICES_INDEX,
            self::INVOICES_SHOW,
            self::INVOICES_PAY,
            self::SUSCRIPTION_INDEX,
            self::SUSCRIPTION_SHOW,
            self::SUSCRIPTIONS_SPECIFIC_USER,
            self::USER_GET_SUSCRIPTION,
            self::USER_SUSCRIPTION_STORE,
            self::USER_SUSCRIPTION_SHOW,
        ];
    }
}
