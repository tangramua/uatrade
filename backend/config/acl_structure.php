<?php
/**
 * Project's required Roles
 * @var array
 */

return
    [
        /*
        |--------------------------------------------------------------------------
        | Roles
        | Project's required roles
        |--------------------------------------------------------------------------
        */
        'roles' => [
            env('SUPER_ADMIN_NAME', 'super_admin') => [
                'display_name'  => 'Super Admin',
                'description'   => 'User with all permissions',
            ],
            'admin' => [
                'display_name'  => 'Admin',
                'description'   => 'User with Admin permissions',
            ],
            'client' => [
                'display_name'  => 'Client', //alias for Employee
                'description'   => 'User with Client permissions',
            ],
            'visitor' => [
                'display_name'  => 'Visitor',
                'description'   => 'User with Visitor permissions',
            ],
            'guest' => [
                'display_name'  => 'Guest',
                'description'   => 'User with Guest permissions',
            ],
        ],
        /*
        |--------------------------------------------------------------------------
        | Permissions
        | Project's required permissions
        |--------------------------------------------------------------------------
        */
        'permissions' => [
            /*
             *
             */
            'identity.ignore' =>[
                'display_name' => 'Ignore user identify',
                'description' => 'Ignore user identify for Admin only',
                'roles' => ['admin'],
            ],
            /*
             * User
             */
            'user.create' =>[
                'display_name' => 'Create User',
                'description' => 'Create new User',
                'roles' => ['admin', 'client'],
            ],
            'user.get' =>[
                'display_name' => 'Get User',
                'description' => 'Get User data',
                'roles' => ['admin', 'client', 'visitor'],
            ],
            'user.edit' =>[
                'display_name' => 'Edit User',
                'description' => 'Edit User data',
                'roles' => ['admin', 'client', 'visitor'],
            ],
            'user.delete' =>[
                'display_name' => 'Delete User',
                'description' => 'Delete User data',
                'roles' => ['admin', 'client', 'visitor'],
            ],
            /*
             * Visitor
             */
            'visitor.create' =>[
                'display_name' => 'Create Visitor',
                'description' => 'Create new Visitor',
                'roles' => ['admin',],
            ],
            'visitor.get' =>[
                'display_name' => 'Get Visitor',
                'description' => 'Get Visitor data',
                'roles' => ['admin', 'client', 'visitor'],
            ],
            'visitor.edit' =>[
                'display_name' => 'Edit Visitor',
                'description' => 'Edit Visitor data',
                'roles' => ['admin', 'visitor'],
            ],
            'visitor.delete' =>[
                'display_name' => 'Delete Visitor',
                'description' => 'Delete Visitor data',
                'roles' => ['admin', 'visitor'],
            ],
            /*
             * User Role and Permission handler
             */
            'user.permission.add' =>[
                'display_name' => 'Add Permission to user',
                'description' => 'Add Permission to user',
                'roles' => ['admin'],
            ],
            'user.permission.remove' =>[
                'display_name' => 'Remove Permission from user',
                'description' => 'Remove Permission from user',
                'roles' => ['admin'],
            ],
            'user.role.add' =>[
                'display_name' => 'Add Role to user',
                'description' => 'Add Role to user',
                'roles' => ['admin'],
            ],
            'user.role.remove' =>[
                'display_name' => 'Remove Role from user',
                'description' => 'Remove Role from user',
                'roles' => ['admin'],
            ],
            'role.create' =>[
                'display_name' => 'Create Role',
                'description' => 'Create new Role',
                'roles' => ['admin'],
            ],
            'role.get' =>[
                'display_name' => 'Get Role',
                'description' => 'Get Role data',
                'roles' => ['admin'],
            ],
            'role.edit' =>[
                'display_name' => 'Edit Role',
                'description' => 'Edit Role data',
                'roles' => ['admin'],
            ],
            'role.delete' =>[
                'display_name' => 'Delete Role',
                'description' => 'Delete Role data',
                'roles' => ['admin'],
            ],
            'permission.create' =>[
                'display_name' => 'Create Permission',
                'description' => 'Create new Permission',
                'roles' => ['admin'],
            ],
            'permission.get' =>[
                'display_name' => 'Get Permission',
                'description' => 'Get Permission data',
                'roles' => ['admin'],
            ],
            'permission.edit' =>[
                'display_name' => 'Edit Permission',
                'description' => 'Edit Permission data',
                'roles' => ['admin'],
            ],
            'permission.delete' =>[
                'display_name' => 'Delete Permission',
                'description' => 'Delete Permission data',
                'roles' => ['admin'],
            ],
            'role.permission.add' =>[
                'display_name' => 'Add permission to User',
                'description' => 'Add permission to User',
                'roles' => ['admin'],
            ],
            'role.permission.remove' =>[
                'display_name' => 'Remove permission to User',
                'description' => 'Remove permission to User',
                'roles' => [],
            ],
            /*
             * Company
             */
            'company.address.create' =>[
                'display_name' => 'Create Address',
                'description' => 'Create new Address',
                'roles' => ['admin', 'client'],
            ],
            'company.address.get' =>[
                'display_name' => 'Get Address',
                'description' => 'Get Address data',
                'roles' => ['admin', 'client', 'visitor'],
            ],
            'company.address.edit' =>[
                'display_name' => 'Edit Address',
                'description' => 'Edit Address data',
                'roles' => ['admin', 'client'],
            ],
            'company.address.delete' =>[
                'display_name' => 'Delete Address',
                'description' => 'Delete Address data',
                'roles' => ['admin', 'client'],
            ],
            'company.category.create' =>[
                'display_name' => 'Create category',
                'description' => 'Create new category',
                'roles' => ['admin'],
            ],
            'company.category.get' =>[
                'display_name' => 'Get category',
                'description' => 'Get category data',
                'roles' => ['admin', 'client', 'visitor'],
            ],
            'company.category.edit' =>[
                'display_name' => 'Edit category',
                'description' => 'Edit category data',
                'roles' => ['admin'],
            ],
            'company.category.delete' =>[
                'display_name' => 'Delete category',
                'description' => 'Delete category data',
                'roles' => ['admin'],
            ],
            'company.product.create' =>[
                'display_name' => 'Create product',
                'description' => 'Create new company product',
                'roles' => ['admin', 'client'],
            ],
            'company.product.get' =>[
                'display_name' => 'Get product',
                'description' => 'Get company product data',
                'roles' => ['admin', 'client', 'visitor'],
            ],
            'company.product.edit' =>[
                'display_name' => 'Edit product',
                'description' => 'Edit company product data',
                'roles' => ['admin', 'client'],
            ],
            'company.product.delete' =>[
                'display_name' => 'Delete product',
                'description' => 'Delete company product data',
                'roles' => ['admin', 'client'],
            ],
            'company.employee.create' =>[
                'display_name' => 'Create employee ',
                'description' => 'Create new company employee',
                'roles' => ['admin', 'client'],
            ],
            'company.employee.get' =>[
                'display_name' => 'Get employee',
                'description' => 'Get company employee data',
                'roles' => ['admin', 'client', 'visitor'],
            ],
            'company.employee.edit' =>[
                'display_name' => 'Edit employee',
                'description' => 'Edit company employee data',
                'roles' => ['admin', 'client'],
            ],
            'company.employee.delete' =>[
                'display_name' => 'Delete employee',
                'description' => 'Delete company employee data',
                'roles' => ['admin', 'client'],
            ],
            'company.create' =>[
                'display_name' => 'Create company',
                'description' => 'Create new company',
                'roles' => ['admin'],
            ],
            'company.get' =>[
                'display_name' => 'Get company',
                'description' => 'Get company data',
                'roles' => ['admin', 'client', 'visitor'],
            ],
            'company.edit' =>[
                'display_name' => 'Edit company',
                'description' => 'Edit company data',
                'roles' => ['admin', 'client'],
            ],
            'company.delete' =>[
                'display_name' => 'Delete company',
                'description' => 'Delete company data',
                'roles' => ['admin'],
            ],
            'company.category.add' =>[
                'display_name' => 'Add category',
                'description' => 'Add company category',
                'roles' => ['admin', 'client'],
            ],
            'company.category.remove' =>[
                'display_name' => 'Remove category',
                'description' => 'Remove company category',
                'roles' => ['admin', 'client'],
            ],
            'company.project.create' =>[
                'display_name' => 'Create project ',
                'description' => 'Create new company project',
                'roles' => ['admin'],
            ],
            'company.project.get' =>[
                'display_name' => 'Get project',
                'description' => 'Get company project data',
                'roles' => ['admin', 'client', 'visitor'],
            ],
            'company.project.edit' =>[
                'display_name' => 'Edit project',
                'description' => 'Edit company project data',
                'roles' => ['admin'],
            ],
            'company.project.delete' =>[
                'display_name' => 'Delete project',
                'description' => 'Delete company project data',
                'roles' => ['admin'],
            ],
            'company.project.add' =>[
                'display_name' => 'Add company project',
                'description' => 'Add company to project',
                'roles' => ['admin'],
            ],
            'company.project.remove' =>[
                'display_name' => 'Remove company project',
                'description' => 'Remove company from project',
                'roles' => ['admin'],
            ],
            'employee.project.add' =>[
                'display_name' => 'Add employee project',
                'description' => 'Add employee to project',
                'roles' => ['admin'],
            ],
            'employee.project.remove' =>[
                'display_name' => 'Remove employee project',
                'description' => 'Remove employee from project',
                'roles' => ['admin'],
            ],
            /*
             * Location
             */
            'location.create' =>[
                'display_name' => 'Create location',
                'description' => 'Create new location',
                'roles' => ['admin', ],
            ],
            'location.get' =>[
                'display_name' => 'Get location',
                'description' => 'Get location data',
                'roles' => ['admin', 'client', 'visitor'],
            ],
            'location.edit' =>[
                'display_name' => 'Edit location',
                'description' => 'Edit location data',
                'roles' => ['admin', ],
            ],
            'location.delete' =>[
                'display_name' => 'Delete location',
                'description' => 'Delete location data',
                'roles' => ['admin', ],
            ],
            /*
             * Event
             */
            'event.create' =>[
                'display_name' => 'Create event',
                'description' => 'Create new event',
                'roles' => ['admin', 'client'],
            ],
            'event.get' =>[
                'display_name' => 'Get event',
                'description' => 'Get event data',
                'roles' => ['admin', 'client', 'visitor'],
            ],
            'event.edit' =>[
                'display_name' => 'Edit event',
                'description' => 'Edit event data',
                'roles' => ['admin', 'client'],
            ],
            'event.delete' =>[
                'display_name' => 'Delete event',
                'description' => 'Delete event data',
                'roles' => ['admin', 'client'],
            ],
            'event.type.create' =>[
                'display_name' => 'Create type',
                'description' => 'Create new event type',
                'roles' => ['admin'],
            ],
            'event.type.get' =>[
                'display_name' => 'Get type',
                'description' => 'Get event type data',
                'roles' => ['admin', 'client', 'visitor'],
            ],
            'event.type.edit' =>[
                'display_name' => 'Edit type',
                'description' => 'Edit event type data',
                'roles' => ['admin'],
            ],
            'event.type.delete' =>[
                'display_name' => 'Delete type',
                'description' => 'Delete event type data',
                'roles' => ['admin'],
            ],
            'event.topic.create' =>[
                'display_name' => 'Create topic',
                'description' => 'Create new event topic',
                'roles' => ['admin'],
            ],
            'event.topic.get' =>[
                'display_name' => 'Get topic',
                'description' => 'Get event topic data',
                'roles' => ['admin', 'client', 'visitor'],
            ],
            'event.topic.edit' =>[
                'display_name' => 'Edit topic',
                'description' => 'Edit event topic data',
                'roles' => ['admin'],
            ],
            'event.topic.delete' =>[
                'display_name' => 'Delete topic',
                'description' => 'Delete event topic data',
                'roles' => ['admin'],
            ],
            'event.language.create' =>[
                'display_name' => 'Create language',
                'description' => 'Create new event language',
                'roles' => ['admin'],
            ],
            'event.language.get' =>[
                'display_name' => 'Get language',
                'description' => 'Get event language data',
                'roles' => ['admin', 'client', 'visitor'],
            ],
            'event.language.edit' =>[
                'display_name' => 'Edit language',
                'description' => 'Edit event language data',
                'roles' => ['admin'],
            ],
            'event.language.delete' =>[
                'display_name' => 'Delete language',
                'description' => 'Delete event language data',
                'roles' => ['admin'],
            ],
            'event.topic.add' =>[
                'display_name' => 'Add topic',
                'description' => 'Add event topic',
                'roles' => ['admin', 'client'],
            ],
            'event.topic.remove' =>[
                'display_name' => 'Remove topic',
                'description' => 'Remove event topic',
                'roles' => ['admin', 'client'],
            ],
            'event.speaker.add' =>[
                'display_name' => 'Add event speaker',
                'description' => 'Add event speaker',
                'roles' => ['admin', 'client'],
            ],
            'event.speaker.remove' =>[
                'display_name' => 'Remove speaker',
                'description' => 'Remove event speaker',
                'roles' => ['admin', 'client'],
            ],
            'event.language.basic.add' =>[
                'display_name' => 'Add event language',
                'description' => 'Add event basic language',
                'roles' => ['admin', 'client'],
            ],
            'event.language.basic.remove' =>[
                'display_name' => 'Remove event language',
                'description' => 'Remove event basic language',
                'roles' => ['admin', 'client'],
            ],
            'event.language.live-translation.add' =>[
                'display_name' => 'Add event live-translation language',
                'description' => 'Add event live-translation language',
                'roles' => ['admin', 'client'],
            ],
            'event.language.live-translation.remove' =>[
                'display_name' => 'Remove event live-translation language',
                'description' => 'Remove event live-translation language',
                'roles' => ['admin', 'client'],
            ],

            /*
             * GeoNames
             */
            'geonames.get' =>[
                'display_name' => 'Get GeoNames',
                'description' => 'Get GeoNames data',
                'roles' => ['admin', 'client', 'visitor'],
            ],

            /*
             * Wechat
             */
            'wechat.group.create' =>[
                'display_name' => 'Create Wechat Group',
                'description' => 'Create new Wechat Group',
                'roles' => ['admin', ],
            ],
            'wechat.group.get' =>[
                'display_name' => 'Get Wechat Group',
                'description' => 'Get Wechat Group data',
                'roles' => ['admin', 'client', 'visitor'],
            ],
            'wechat.group.edit' =>[
                'display_name' => 'Edit Wechat Group',
                'description' => 'Edit Wechat Group data',
                'roles' => ['admin', ],
            ],
            'wechat.group.delete' =>[
                'display_name' => 'Delete Wechat Group',
                'description' => 'Delete Wechat Group data',
                'roles' => ['admin', ],
            ],

            /*
             * RocketChat
             */
            'rocketchat.group.create' =>[
                'display_name' => 'Create RocketChat Group',
                'description' => 'Create new RocketChat Group',
                'roles' => ['admin', ],
            ],
            'rocketchat.group.get' =>[
                'display_name' => 'Get RocketChat Group',
                'description' => 'Get RocketChat Group data',
                'roles' => ['admin', 'client', 'visitor'],
            ],
            'rocketchat.group.edit' =>[
                'display_name' => 'Edit RocketChat Group',
                'description' => 'Edit RocketChat Group data',
                'roles' => ['admin', ],
            ],
            'rocketchat.group.delete' =>[
                'display_name' => 'Delete RocketChat Group',
                'description' => 'Delete RocketChat Group data',
                'roles' => ['admin', ],
            ],
            'rocketchat.token.get' =>[
                'display_name' => 'Get RocketChat Auth Token',
                'description' => 'Get RocketChat Auth Token',
                'roles' => ['admin', 'client', 'visitor'],
            ],
            /*
             * Testimonials
             */
            'testimonials.create' =>[
                'display_name' => 'Create Testimonials',
                'description' => 'Create new Testimonials Group',
                'roles' => ['admin', 'client', 'visitor'],
            ],
            'testimonials.get' =>[
                'display_name' => 'Get Testimonials',
                'description' => 'Get Testimonials data',
                'roles' => ['admin', 'client', 'visitor'],
            ],
            'testimonials.edit' =>[
                'display_name' => 'Edit Testimonials ',
                'description' => 'Edit Testimonials data',
                'roles' => ['admin', ],
            ],
            'testimonials.delete' =>[
                'display_name' => 'Delete Testimonials',
                'description' => 'Delete Testimonials data',
                'roles' => ['admin', ],
            ],
            /*
             * Certificates
             */
            'company.certificate.create' =>[
                'display_name' => 'Create company certificate',
                'description' => 'Create new company certificate',
                'roles' => ['admin', ],
            ],
            'company.certificate.get' =>[
                'display_name' => 'Get company certificate',
                'description' => 'Get company certificates',
                'roles' => ['admin', 'client', 'visitor'],
            ],
            'company.certificate.edit' =>[
                'display_name' => 'Edit company certificate',
                'description' => 'Edit company certificate',
                'roles' => ['admin', ],
            ],
            'company.certificate.delete' =>[
                'display_name' => 'Delete company certificate',
                'description' => 'Delete company certificate',
                'roles' => ['admin', ],
            ],
            'company.certificate.add' =>[
                'display_name' => 'Add certificate to company',
                'description' => 'Add certificate to company',
                'roles' => ['admin', ],
            ],
            'company.certificate.remove' =>[
                'display_name' => 'Remove certificate to company',
                'description' => 'Remove certificate to company',
                'roles' => ['admin', ],
            ],
            /*
             * Notification ContactUs
             */
            'contacts.recipient.create' =>[
                'display_name' => 'Create ContactUs recipient',
                'description' => 'Create new ContactUs recipient',
                'roles' => ['admin'],
            ],
            'contacts.recipient.get' =>[
                'display_name' => 'Get ContactUs recipient',
                'description' => 'Get ContactUs recipient',
                'roles' => ['admin', 'client', 'visitor'],
            ],
            'contacts.recipient.edit' =>[
                'display_name' => 'Edit ContactUs recipient',
                'description' => 'Edit ContactUs recipient',
                'roles' => ['admin'],
            ],
            'contacts.recipient.delete' =>[
                'display_name' => 'Delete ContactUs recipient',
                'description' => 'Delete ContactUs recipient',
                'roles' => ['admin'],
            ],
            /*
             * Page
             */
            'page.create' =>[
                'display_name' => 'Create Page',
                'description' => 'Create new Page',
                'roles' => ['admin'],
            ],
            'page.get' =>[
                'display_name' => 'Get Page',
                'description' => 'Get Page',
                'roles' => ['admin', 'client', 'visitor'],
            ],
            'page.edit' =>[
                'display_name' => 'Edit Page',
                'description' => 'Edit Page',
                'roles' => ['admin'],
            ],
            'page.delete' =>[
                'display_name' => 'Delete Page',
                'description' => 'Delete Page',
                'roles' => ['admin'],
            ],
            /*
             * Favorites
             */
            'favorites.get' =>[
                'display_name' => 'Get Favorites',
                'description' => 'Get Favorites',
                'roles' => ['admin', 'client', 'visitor'],
            ],
            'favorites.add' =>[
                'display_name' => 'Add Favorites',
                'description' => 'Add your Favorites',
                'roles' => ['admin', 'client', 'visitor'],
            ],
            'favorites.remove' =>[
                'display_name' => 'Remove Favorites',
                'description' => 'Remove your Favorites',
                'roles' => ['admin', 'client', 'visitor'],
            ],
        ],
    ];
