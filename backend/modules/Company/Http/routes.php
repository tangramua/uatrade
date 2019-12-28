<?php
Route::namespace('Admin')->prefix('/admin')->middleware('jwt.auth')->group(function () {
    Route::prefix('company')->name('company.')->group(function () {

        Route::resource('address', 'AddressController')->only(['index', 'store', 'show', 'update', 'destroy'])->middleware("permission");

        Route::get('/{company_id}/certificate/list', 'CompanyController@getCompanyCertificates')->middleware("permission:company.certificate.get");
        Route::post('/{company_id}/certificate/{certificate_id}', 'CertificateController@addCertificateToCompany')->middleware("permission:company.certificate.add");
        Route::delete('/{company_id}/certificate/{certificate_id}', 'CertificateController@removeCertificateFromCompany')->middleware("permission:company.certificate.remove");

        Route::get('/{company_id}/export_country/list', 'CompanyController@getCompanyExportCountries')->middleware("permission:company.edit");
        Route::post('/{company_id}/export_country/{country_id}', 'CompanyController@addExportCountryToCompany')->middleware("permission:company.edit");
        Route::delete('/{company_id}/export_country/{country_id}', 'CompanyController@removeExportCountryFromCompany')->middleware("permission:company.edit");

        Route::prefix('certificate')->name('certificate.')->group(function () {
            Route::match(['get', 'post'], '/paginate', 'CertificateController@paginate')->middleware("permission:company.certificate.get");
            Route::resource('', 'CertificateController', ['parameters' => ['' => 'certificate']])->only(['index', 'store', 'show', 'update', 'destroy'])->middleware("permission");

        });

        Route::prefix('category')->name('category.')->group(function () {
            Route::match(['get', 'post'], '/paginate', 'CategoryController@paginate')->middleware("permission:company.category.get");
            Route::resource('', 'CategoryController', ['parameters' => ['' => 'category']])->only(['index', 'store', 'show', 'update', 'destroy'])->middleware("permission");

            Route::post('/add/{company_id}/{category_id}', 'CompanyController@addCategoryToCompany')->middleware("permission:company.category.add");
            Route::post('/remove/{company_id}/{category_id}', 'CompanyController@removeCategoryFromCompany')->middleware("permission:company.category.remove");
            Route::get('/list/{company_id}', 'CompanyController@listCategoriesFromCompany')->middleware("permission:company.get");
        });

        Route::prefix('project')->name('project.')->group(function () {
            Route::match(['get', 'post'], '/paginate', 'ProjectController@paginate')->name('paginate')->middleware("permission:company.project.get");
            Route::resource('', 'ProjectController', ['parameters' => ['' => 'project']])->only(['index', 'store', 'show', 'update', 'destroy'])->middleware("permission");

            Route::post('/{project_id}/employee/{employee_id}', 'ProjectController@addEmployeeToProject')->middleware("permission:employee.project.add");
            Route::delete('/{project_id}/employee/{employee_id}', 'ProjectController@removeEmployeeFromProject')->middleware("permission:employee.project.remove");
            Route::post('/{project_id}/company/{company_id}', 'ProjectController@addCompanyToProject')->middleware("permission:company.project.add");
            Route::delete('/{project_id}/company', 'ProjectController@removeCompanyFromProject')->middleware("permission:company.project.remove");
            Route::get('/{project_id}/members', 'ProjectController@membersList')->middleware("permission:company.project.get");

        });
        Route::get('/{company_id}/project/list', 'ProjectController@listProjectFromCompany')->middleware("permission:company.get");


        Route::prefix('product')->name('product.')->group(function () {
            Route::match(['get', 'post'], '/paginate', 'ProductController@paginate')->middleware("permission:company.product.get");
            Route::resource('', 'ProductController', ['parameters' => ['' => 'product']])->only(['index', 'store', 'show', 'update', 'destroy'])->middleware("permission");

            Route::get('/list/{company_id}', 'CompanyController@listProductsFromCompany')->middleware("permission:company.get");
        });

        Route::prefix('employee')->name('employee.')->group(function () {
            Route::match(['get', 'post'], '/paginate', 'EmployeeController@paginate')->middleware("permission:company.employee.get");
            Route::resource('', 'EmployeeController', ['parameters' => ['' => 'employee']])->only(['index', 'store', 'show', 'update', 'destroy'])->middleware("permission");

            Route::get('/list/{company_id}', 'CompanyController@listEmployeesFromCompany')->middleware("permission:company.get")
                ->where('company_id', '[0-9]+');
        });

        Route::get('{company_id}/wechat-group/list', 'CompanyController@listWechatGroupsFromCompany')->middleware("permission:company.get")->middleware("permission:wechat.get");

        Route::match(['get', 'post'], '/paginate', 'CompanyController@paginate')->middleware("permission:company.get");
        Route::resource('', 'CompanyController', ['parameters' => ['' => 'company']])->only(['index', 'store', 'show', 'update', 'destroy'])->middleware("permission");
    });
    Route::get('/employee/{employee_id}/project/list', 'ProjectController@listProjectFromEmployee')->middleware("permission:employee.get");

});

Route::namespace('Client')->prefix('client')->group(function () {
    Route::prefix('company')->name('company.')->group(function () {

        Route::resource('address', 'AddressController')->only(['index', 'store', 'show', 'update', 'destroy'])->middleware("permission");

        Route::prefix('category')->name('category.')->group(function () {
            Route::match(['get', 'post'], '/paginate', 'CategoryController@paginate')->middleware("permission:company.category.get");
            Route::resource('', 'CategoryController', ['parameters' => ['' => 'category']])->only(['index', 'show'])->middleware("permission");

            Route::post('/add/{company_id}/{category_id}', 'CompanyController@addCategoryToCompany')->middleware("permission:company.category.add");
            Route::post('/remove/{company_id}/{category_id}', 'CompanyController@removeCategoryFromCompany')->middleware("permission:company.category.remove");
            Route::get('/list/{company_id}', 'CompanyController@listCategoriesFromCompany')->middleware("permission:company.get");
        });

        Route::prefix('product')->name('product.')->group(function () {
            Route::match(['get', 'post'], '/paginate', 'ProductController@paginate')->middleware("permission:company.product.get");
            Route::resource('', 'ProductController', ['parameters' => ['' => 'product']])->only(['index', 'store', 'show', 'update', 'destroy'])->middleware("permission");

            Route::get('/list/{company_id}', 'CompanyController@listProductsFromCompany')->middleware("permission:company.get");
        });

        Route::prefix('employee')->name('employee.')->group(function () {
            Route::match(['get', 'post'], '/paginate', 'EmployeeController@paginate')->middleware("permission:company.employee.get");
            Route::resource('', 'EmployeeController', ['parameters' => ['' => 'employee']])->only(['index', 'store', 'show', 'update', 'destroy'])->middleware("permission");

            Route::get('/list/{company_id}', 'CompanyController@listEmployeesFromCompany')->middleware("permission:company.get")
                ->where('company_id', '[0-9]+');
        });

        Route::get('wechat-group/list', 'CompanyController@listWechatGroupsFromAuthUserCompany')->middleware("permission:company.get")->middleware("permission:wechat.get");

        Route::match(['get', 'post'], '/paginate', 'CompanyController@paginate')->middleware("permission:company.get");
        Route::resource('', 'CompanyController', ['parameters' => ['' => 'company']])->only(['index', 'show', 'update'])->middleware("permission");
    });
});

Route::namespace('Guest')->prefix('guest')->group(function () {
    Route::prefix('company')->name('company.')->group(function () {

        Route::resource('address', 'AddressController')->only(['index', 'show']);

        Route::prefix('certificate')->name('certificate.')->group(function () {
            Route::match(['get', 'post'], '/paginate', 'CertificateController@paginate');
            Route::resource('', 'CertificateController', ['parameters' => ['' => 'certificate']])->only(['index', 'show']);

        });
        Route::prefix('project')->name('project.')->group(function () {
            Route::match(['get', 'post'], '/paginate', 'ProjectController@paginate')->name('paginate');
            Route::resource('', 'ProjectController', ['parameters' => ['' => 'project']])->only(['index', 'show']);
        });


        Route::prefix('category')->name('category.')->group(function () {
            Route::match(['get', 'post'], '/paginate', 'CategoryController@paginate');
            Route::get('/tree', 'CategoryController@categoriesTree');
            Route::resource('', 'CategoryController', ['parameters' => ['' => 'category']])->only(['index', 'show']);

            Route::get('/list/{company_id}', 'CompanyController@listCategoriesFromCompany');
        });

        Route::prefix('product')->name('product.')->group(function () {
            Route::match(['get', 'post'], '/paginate', 'ProductController@paginate');
            Route::resource('', 'ProductController', ['parameters' => ['' => 'product']])->only(['index', 'show']);

            Route::get('/list/{company_id}', 'CompanyController@listProductsFromCompany');
        });

        Route::prefix('employee')->name('employee.')->group(function () {
            Route::match(['get', 'post'], '/paginate', 'EmployeeController@paginate');
            Route::resource('', 'EmployeeController', ['parameters' => ['' => 'employee']])->only(['index', 'show']);

            Route::get('/list/{company_id}', 'CompanyController@listEmployeesFromCompany')->where('company_id', '[0-9]+');
        });

        Route::get('{company_id}/wechat-group/list', 'CompanyController@listWechatGroupsFromCompany');

        Route::match(['get', 'post'], '/paginate', 'CompanyController@paginate');
        Route::resource('', 'CompanyController', ['parameters' => ['' => 'company']])->only(['index', 'show']);
    });
});
