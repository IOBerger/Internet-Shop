//ПРОБЛЕМА: в случае ошибки форма обновляется. Как решить? Сделать буттон вместо инпут?

$(document).ready(function() {

    $('.menu-item-list').on('click', function(e){
        e.preventDefault();
        if($(".menu-category").is(":visible")){
            $(".menu-category").hide();
        } else{
            $(".menu-category").show();
        }
    });

$('#menu-item-auth').on('click', function(e){
    e.preventDefault();
    if($("#auth-div").is(":visible")){
        $("#auth-div").hide();
    } else{
        $("#auth-div").show();
        $("#registration-div").hide();
    }
});

$('#menu-item-registration').on('click', function(e){
    e.preventDefault();
    if($("#registration-div").is(":visible")){
        $("#registration-div").hide();
    } else{
        $("#registration-div").show();
        $("#auth-div").hide();
    }
});

/*
    //Если кто-то отправляет отзыв
    $("#sublit-search").submit(function() {
 
        if($("#id").val().length<3){
            alert("Введите больше трёх символов");
        }
        
        return false;

    });    
*/

    $("#review-form").submit(function() {
 
        urlParams = new URLSearchParams(window.location.search);
        params = {};
        
        urlParams.forEach((p, key) => {
          params[key] = p;
        });

        let action = 'add_review';

        if(params['action']=='edit'){
            action = 'edit_review';
        }

        //Создаём объект с данными отзыва
        let formData = {
            id: $("#id").val(), 
            rate: $("#rate").val(), 
            comment: $("#comment").val(),
            product: $("#product").val(),
            action: action
        };

        
        //Посылаем запрос
        $.ajax({
            url: 'processing/process_data.php',
            method: 'post',
            data: JSON.stringify(formData),
            //В случае успеха
            success: function(data) {
                let dataObj = JSON.parse(data);
                //Если отзыв успешно добавлен и передан рейтинг
                if('success' in dataObj && dataObj['success']==1){
                    //$('#rate-value').html(dataObj.rate);
                    alert("Успешно!");
                    $(location).attr('href','?page=review');
                }else{
                    alert(dataObj['error']);
                }
                
            },
            //В случае ошибки
            error: function(xhr, textStatus) {
                alert([xhr.status, textStatus]);
            }         
        })

        //Предотвращаем отправку формы
        return false;
    
    });
    
    let changeReviewActive = function(id,status) {

        if(!confirm("Сменить видимость?")){
            return;
        }

        //Создаём объект с данными отзыва
        let formData = {
            id: id,
            status: status,
            action: 'delete_review'
        };
        //alert(formData['id']);
        //Посылаем запрос
        $.ajax({
            url: 'processing/process_data.php',
            method: 'post',
            data: JSON.stringify(formData),
            //В случае успеха
            success: function(data) {
                let dataObj = JSON.parse(data);
                if('success' in dataObj && dataObj['success']==1){
                    alert("Успешно!");
                    location.reload();
                }else{
                    alert(dataObj['error']);
                }
                
            },
            //В случае ошибки
            error: function(xhr, textStatus) {
                alert([xhr.status, textStatus]);
            }         
        })

        //Предотвращаем отправку формы
        return false;
    
    }
    
    $(document).on('click',".review-delete-button",function(){
        changeReviewActive($(this).attr('data-id'),0);
    });    

    $(document).on('click',".review-ressurect-button",function(){
        changeReviewActive($(this).attr('data-id'),1);
    });      

    let DeleteReviewForever = function(id) {
        //alert('!');
        let deleteOrNot = confirm("Отзыв удалится навечно. Подтверждаете?");
        if(!deleteOrNot){
            return false;
        }
    
        //Создаём объект с данными заказа
        let formData = {
            id: id, 
            action: 'delete_forever_review'
        };
        
        //Посылаем запрос
        $.ajax({
            url: 'processing/process_data.php',
            method: 'post',
            data: JSON.stringify(formData),
            //В случае успеха
            success: function(data) {
                let dataObj = JSON.parse(data);
                //Если товар успешно добавлен
                if('success' in dataObj && dataObj['success']==1){
                    alert("Отзыв удалён!");
                    location.reload();
                }else{
                    alert(dataObj['error']);
                }
            },
            //В случае ошибки
            error: function(xhr, textStatus) {
                alert([xhr.status, textStatus]);
            }
        });
    
        //Предотвращаем отправку формы
        return false;
    
    }
    
    $(document).on('click',".review-delete-forever-button",function(){
        DeleteReviewForever($(this).attr('data-id'));
    });  
    

    //Если кто-то отправляет заказ
    $("#order-form").submit(function() {
        
        //Создаём объект с данными заказа
        let formData = {
            product: $("#product").val(), 
            comment: $("#comment").val(),
            action: 'add_order'
        };
        
        //Посылаем запрос
        $.ajax({
            url: 'processing/process_data.php',
            method: 'post',
            data: JSON.stringify(formData),
            //В случае успеха
            success: function(data) {
                let dataObj = JSON.parse(data);
                //Если заказ успешно добавлен
                if('success' in dataObj && dataObj['success']==1){
                    alert("Заказ принят!");
                    $(location).attr('href','index.php');
                }else{
                    alert(dataObj['error']);
                }
            },
            //В случае ошибки
            error: function(xhr, textStatus) {
                alert([xhr.status, textStatus]);
            }
        });

        //Предотвращаем отправку формы
        return false;
    
    });    

    //Если кто-то добавляет товар
    $("#add-product-form").submit(function() {
        
        //Создаём объект с данными товара
        let formData = {
            product: $('#product').val(), 
            color: $("#color").val(),
            size: $("#size").val(),
            price: $("#price").val(),            
            annotation: $("#annotation").val(),            
            category: $("#category").val(),  
            product_before: $("#product-before").val(),           
            product_after: $("#product-after").val(),           
            action: 'add_product'
        };
        
        //Посылаем запрос
        $.ajax({
            url: 'processing/process_data.php',
            method: 'post',
            data: JSON.stringify(formData),
            //В случае успеха
            success: function(data) {
                let dataObj = JSON.parse(data);
                //Если товар успешно добавлен
                if('success' in dataObj && dataObj['success']==1){
                    console.log(data);
                    alert("Товар добавлен!");
                    $(location).attr('href','?page=list');
                }else{
                    alert(dataObj['error']);
                }
            },
            //В случае ошибки
            error: function(xhr, textStatus) {
                alert([xhr.status, textStatus]);
            }
        });

        //Предотвращаем отправку формы
        return false;
    
    });    


    $("#edit-product-form").submit(function() {
        
        //Создаём объект с данными товара
        let formData = {
            id: $('#product-id').val(),
            product: $('#product').val(), 
            color: $("#color").val(),
            size: $("#size").val(),
            price: $("#price").val(),     
            annotation: $("#annotation").val(),
            category: $("#category").val(),
            product_before: $("#product-before").val(),           
            product_after: $("#product-after").val(),                        
            action: 'edit_product'
        };
        
        //Посылаем запрос
        $.ajax({
            url: 'processing/process_data.php',
            method: 'post',
            data: JSON.stringify(formData),
            //В случае успеха
            success: function(data) {
                let dataObj = JSON.parse(data);
                //Если товар успешно добавлен
                if('success' in dataObj && dataObj['success']==1){
                    alert("Товар изменён!");   
                    $(location).attr('href','?page=list');
                }else{
                    alert(dataObj['error']);
                }
            },
            //В случае ошибки
            error: function(xhr, textStatus) {
                alert([xhr.status, textStatus]);
            }
        });

        //Предотвращаем отправку формы
        return false;
    
    });    
    
    let changeActive = function(id,status) {
        //alert('!');
        deleteOrNot = confirm("Изменить статус товара?");
        if(!deleteOrNot){
            return false;
        }

        //Создаём объект с данными заказа
        let formData = {
            id: id, 
            action: 'activation_change_product',
            status: status
        };
        
        //Посылаем запрос
        $.ajax({
            url: 'processing/process_data.php',
            method: 'post',
            data: JSON.stringify(formData),
            //В случае успеха
            success: function(data) {
                let dataObj = JSON.parse(data);
                //Если товар успешно добавлен
                if('success' in dataObj && dataObj['success']==1){
                    alert("Статус товара изменён!");
                    location.reload();
                }else{
                    alert(dataObj['error']);
                }
            },
            //В случае ошибки
            error: function(xhr, textStatus) {
                alert([xhr.status, textStatus]);
            }
        });

        //Предотвращаем отправку формы
        return false;
    
    }

    $(document).on('click',".product-delete-button",function(){
        changeActive($(this).attr('data-id'),0);
    });    
    $(document).on('click',".product-ressurect-button",function(){
        changeActive($(this).attr('data-id'),1);
    });      

    let DeleteForever = function(id) {
        //alert('!');
        let deleteOrNot = confirm("Товар удалится навечно. Подтверждаете?");
        if(!deleteOrNot){
            return false;
        }

        //Создаём объект с данными заказа
        let formData = {
            id: id, 
            action: 'delete_forever'
        };
        
        //Посылаем запрос
        $.ajax({
            url: 'processing/process_data.php',
            method: 'post',
            data: JSON.stringify(formData),
            //В случае успеха
            success: function(data) {
                let dataObj = JSON.parse(data);
                //Если товар успешно добавлен
                if('success' in dataObj && dataObj['success']==1){
                    alert("Товар удалён!");
                    $(location).attr('href','?page=list');
                }else{
                    alert(dataObj['error']);
                }
            },
            //В случае ошибки
            error: function(xhr, textStatus) {
                alert([xhr.status, textStatus]);
            }
        });

        //Предотвращаем отправку формы
        return false;
    
    }

    $(document).on('click',".product-delete-forever-button",function(){
        DeleteForever($(this).attr('data-id'));
    });  
  
    $("#auth-form").submit(function() {
        
        
        //Создаём объект с логином паролем
        let formData = {
            login: $("#auth-login").val(), 
            password: $("#auth-password").val(),
            action: 'auth'
        };
        
        //Посылаем запрос
        $.ajax({
            url: 'processing/auth.php',
            method: 'post',
            data: JSON.stringify(formData),
            //В случае успеха
            success: function(data) {
                
                
                let dataObj = JSON.parse(data);
                //
                
                if('success' in dataObj && dataObj['success']==1){
                    alert("Вы авторизованы!");
                    location.reload();
                }else if('error' in dataObj){
                    alert(dataObj['error']);
                }
            },
            //В случае ошибки
            error: function(xhr, textStatus) {
                alert([xhr.status, textStatus]);
            }         
        })

        //Предотвращаем отправку формы
        return false;
    
    });  
    
    $("#registration-form").submit(function() {
        
        
        //Создаём объект с логином паролем
        let formData = {
            login: $("#registration-login").val(), 
            password: $("#registration-password").val(),
            password2: $("#registration-password2").val(),
            action: 'registration'
        };
        
        //Посылаем запрос
        $.ajax({
            url: 'processing/auth.php',
            method: 'post',
            data: JSON.stringify(formData),
            //В случае успеха
            success: function(data) {          
                let dataObj = JSON.parse(data);
                
                if('success' in dataObj && dataObj['success']==1){
                    alert("Вы зарегистрированы!");
                    location.reload();
                }else if('error' in dataObj){
                    alert(dataObj['error']);
                }
            },
            //В случае ошибки
            error: function(xhr, textStatus) {
                alert([xhr.status, textStatus]);
            }         
        })

        //Предотвращаем отправку формы
        return false;
    
    });  
    
    $("#exit-link").click(function() {
        
        //Создаём объект
        let formData = {
            action: 'exit'
        };
        
        //Посылаем запрос
        $.ajax({
            url: 'processing/exit.php',
            method: 'post',
            data: JSON.stringify(formData),
            //В случае успеха
            success: function(data) {
                let dataObj = JSON.parse(data);
                if('success' in dataObj && dataObj['success']==1){
                    alert("Вы вышли!");
                    location.reload();
                }else{
                    alert("Ошибка выхода!");
                }
            },
            //В случае ошибки
            error: function(xhr, textStatus) {
                alert([xhr.status, textStatus]);
            }         
        })

        return false;

    });

    //let pageNumber = 2;

    $("#products-next-button").click(function() {
    
        let pageNumber = $(this).attr('data-page');

        let formData = {
            page: pageNumber,
            category: $(this).attr('data-category'),
            action: 'next_list'
        };
        
        let formDataCount = {
            action: 'count_list'
        };

        let count = 99999999999;

        //СДЕЛАТЬ ПО-ДРУГОМУ ПЕРЕДАВАТЬ ПАРАМЕТР К PHP

        //Посылаем запрос
        $.ajax({
            url: 'processing/list.php',
            method: 'post',
            data: JSON.stringify(formDataCount),
            //В случае успеха
            asyc: false,
            success: (data) => {
                let dataObj = JSON.parse(data);
                if('success' in dataObj && dataObj['success']==1){
                    count = dataObj['result'];
                }
            },
            //В случае ошибки
            error: function(xhr, textStatus) {
                alert([xhr.status, textStatus]);
            }         
        });

        $.ajax({
            url: 'processing/list.php',
            method: 'post',
            data: JSON.stringify(formData),
            asyc: false,
            //В случае успеха
            success: function(data){
                $("#products").append(data);
                pageNumber++;
                $("#products-next-button").attr('data-page', pageNumber);
                //alert(pageNumber);
                if(count <= (pageNumber - 1) * 9){
                    $('#products-next-button').css('display', 'none');
                }
            },
            //В случае ошибки
            error: function(xhr, textStatus) {
                alert([xhr.status, textStatus]);
            }         
        });

    });

    $("#add-category-form").submit(function() {
       // alert(1);
    //Создаём объект с данными товара
    let formData = {
        title: $('#title').val(), 
        sort: $('#sort').val(), 
        description: $("#description").val(),
        parent: $("#parent").val(),
        action: 'add'
    };
    
    //Посылаем запрос
    $.ajax({
        url: 'processing/process_categories.php',
        method: 'post',
        data: JSON.stringify(formData),
        //В случае успеха
        success: function(data) {
            let dataObj = JSON.parse(data);
            //Если товар успешно добавлен
            if('success' in dataObj && dataObj['success']==1){
                alert("Добавлено!");
                location.reload();
            }else{
                alert(dataObj['error']);
            }
        },
        //В случае ошибки
        error: function(xhr, textStatus) {
            alert([xhr.status, textStatus]);
        }
    });

    //Предотвращаем отправку формы
    return false;
    
    });    

    $("#edit-category-form").submit(function() {
        
        //Создаём объект с данными товара
        let formData = {
            title: $('#title').val(), 
            sort: $('#sort').val(), 
            description: $("#description").val(),
            parent: $("#parent").val(),
            id: $("#category-id").val(),
            action: 'edit'
        };
        
        //Посылаем запрос
        $.ajax({
            url: 'processing/process_categories.php',
            method: 'post',
            data: JSON.stringify(formData),
            //В случае успеха
            success: function(data) {
                let dataObj = JSON.parse(data);
                //Если товар успешно добавлен
                if('success' in dataObj && dataObj['success']==1){
                    alert("Изменено!");   
                    location.reload();
                }else{
                    alert(dataObj['error']);
                }
            },
            //В случае ошибки
            error: function(xhr, textStatus) {
                alert([xhr.status, textStatus]);
            }
        });

        //Предотвращаем отправку формы
        return false;
    
    });    



    let changeCategoryActive = function(id,status){
        deleteOrNot = confirm("Изменить видимость?");
        if(!deleteOrNot){
            return false;
        }

        //Создаём объект с данными заказа
        let formData = {
            id: id,
            status: status, 
            action: 'delete'
        };
        
        //Посылаем запрос
        $.ajax({
            url: 'processing/process_categories.php',
            method: 'post',
            data: JSON.stringify(formData),
            //В случае успеха
            success: function(data) {
                let dataObj = JSON.parse(data);
                //Если товар успешно добавлен
                if('success' in dataObj && dataObj['success']==1){
                    alert("Видимость изменена!");
                    location.reload();
                }else{
                    alert(dataObj['error']);
                }
            },
            //В случае ошибки
            error: function(xhr, textStatus) {
                alert([xhr.status, textStatus]);
            }
        });

        //Предотвращаем отправку формы
        return false;
    }

    $(document).on('click',"#category-delete",function(){
        changeCategoryActive($(this).attr('data-id'),0);
    });    
    $(document).on('click',"#category-ressurect",function(){
        changeCategoryActive($(this).attr('data-id'),1);
    });      
  
});

let DeleteCategoryForever = function(id) {
    //alert('!');
    let deleteOrNot = confirm("Категория удалится навечно. Подтверждаете?");
    if(!deleteOrNot){
        return false;
    }

    //Создаём объект с данными заказа
    let formData = {
        id: id, 
        action: 'delete_forever'
    };
    
    //Посылаем запрос
    $.ajax({
        url: 'processing/process_categories.php',
        method: 'post',
        data: JSON.stringify(formData),
        //В случае успеха
        success: function(data) {
            let dataObj = JSON.parse(data);
            //Если товар успешно добавлен
            if('success' in dataObj && dataObj['success']==1){
                alert("Категория удалён!");
                $(location).attr('href','?page=list');
            }else{
                alert(dataObj['error']);
            }
        },
        //В случае ошибки
        error: function(xhr, textStatus) {
            alert([xhr.status, textStatus]);
        }
    });

    //Предотвращаем отправку формы
    return false;

}

$(document).on('click',".category-delete-forever-button",function(){
    DeleteCategoryForever($(this).attr('data-id'));
});  


