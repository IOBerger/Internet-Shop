<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/style.css">
    <script src="scripts/jquery-3.6.1.min.js"></script>
    <script src="scripts/script.js"></script>
    <title><?=$title ?></title>
</head>
<body>
    <div class="container">

<header>    
<h1 class="title">Замечательные товары</h1>
<div class="form-wrp">
<form class="form-search" 
    action="" 
    method="get"
>
    <input class="form-search-input"  type="text" id="search" name="q" placeholder="Введите текст для поиска или номер продукта" value="<?=htmlspecialchars($_GET['q']); ?>" required="required">
    <input class="submit-search" id="submit-search" type="submit" value="Найти">
    <input type="hidden" name="page" value="list" required="required">
    <input type="hidden" name="sort" value="<?= htmlspecialchars($sort); ?>" required="required">
    <input type="hidden" name="order" value="<?= htmlspecialchars($order); ?>" required="required">
</form>
<form action="">
<input type="hidden" name="page" value="<?= htmlspecialchars($_GET['page']); ?>" required="required">
<button class="submit-search" >Сброс</button>
  </form>
</div>
</header>


        <nav class="nav">

            <div class="menu-wrp-main">
    
                <? foreach ($menu as $key => $item):?>
                    <div class="menu-wrp-item">
                    <?php if($key == 'list'): ?>
                        <div class="menu-item-list">
                    <?php endif; ?>
                    <? if ($item['align'] == 'left' and in_array($rights, $item['rights'])): ?>
                        <a class="menu-item 
                            <? if ($page == $key): ?>
                                menu-item-active
                            <?php endif; ?>
                        " href="?page=<?=$key?>"><?=$item['title']?></a>
                        <?php if($key == 'list'): ?>
                            
                            
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if($key == 'list'): ?>
                        </div>
                    <?php endif; ?>                    
                    </div>    
                <? endforeach; ?>
                
            </div>


            <div class="menu-wrp-auth">

                <? foreach ($menu as $key => $item):?>
                    
                    <div class="menu-wrp-item">
                    <? if ($item['align'] == 'right' and in_array($rights, $item['rights'])): ?>
                        <a class="menu-item 
                            <? if ($page == $key): ?>
                                menu-item-active
                            <?php endif; ?>
                        " 
                        id="menu-item-<?=$key?>"
                        href="?page=<?=$key?>"
                    ><?=$item['title']?></a>
                    <?php endif; ?>
                        <div id="<?=$key?>-div">
                        <? if ($key=='auth'): ?>
                                
                            
                                <form 
                                    id="auth-form" name="auth" method="post" >
                                    <div class="main">
                                        <div class="form-row">
                                            <label for="product">
                                                Логин
                                            </label>
                                            <input type="text" id="auth-login" name="login" required="required">
                                        </div>
                                        <div class="form-row">
                                            <label for="color">
                                                Пароль
                                            </label>
                                            <input type="password" id="auth-password" name="password">
                                        </div>
                                        <div class="form-row">
                                            <input class="auth-button" type="submit" value="Отправить">
                                        </div> 
                                    </div>
                                </form>
                            
                        <?php elseif($key=='registration'): ?>
  
    <form 
        id="registration-form" name="registration" method="post" >
        <div class="main">
        <div class="form-row">
            <label for="product">
                Логин
            </label>
            <input type="text" id="registration-login" name="login" required="required">
        </div>
        <div class="form-row">
            <label for="color">
                Пароль
            </label>
            <input type="password" id="registration-password" name="password">
        </div>
        <div class="form-row">
            <label for="color">
                Снова пароль
            </label>
            <input type="password" id="registration-password2" name="password2">
        </div>
        <div class="form-row">
            <input class="registration-button" type="submit" value="Отправить">
        </div> 
    </div>
    </form>
  
                        <?php endif; ?>
                        </div>
                    </div>
                    
                <? endforeach; ?>
    
                <? if($rights != -1): ?>
                    <p class="menu-text">
                        Здравствуйте, <b><?= $_SESSION['login'] ?></b>! 
                    </p>   
                    <a href="" id="exit-link" class="menu-item menu-item-auth">Выйти</a>
                <?php endif; ?>
            
            </div>

        </nav>



                            <div class="menu-category">

                                    <?php if($rights==1): ?>
                                        <a class="category-manage-link" href="?page=category_manage">Добавить категорию</a>
                                    <?php endif; ?>
                                    <a class="menu-category-all-link" href="?page=list">Все товары</a>
                                <? foreach ($categoriesMain as $main):?>
                                    <?php if($rights == 1 or $main['is_active']==1): ?>
                                    <div class="menu-category-item 
                                        <?php if($main['is_active']==0): ?>
                                            menu-category-item-deleted
                                        <?php endif; ?>
                                    ">
                                        <strong><a class="menu-category-item" href="?page=list&category=<?=$main['id']?>"><?=$main['title']?></a></strong>
                                        <?php if($rights == 1): ?>
                                            <i>(<?=$main['sort']?>)</i>
                                        <?php endif; ?>
                                        <p><?=$main['description']?></p>
                                        <div class="menu-category-sub">
                                            <? if(array_key_exists($main['id'],$categoriesChild)): foreach ($categoriesChild[$main['id']] as $child):?>
                                                <div 
                                                    <?php if($child['is_active']==0): ?>
                                                        class="menu-category-item-deleted"
                                                    <?php endif; ?>
                                                >
                                                <?php if($rights == 1 or $child['is_active']==1): ?>

                                                <strong><a class="menu-category-item
                                                    

                                                " href="?page=list&category=<?=$child['id']?>"><?=$child['title']?></a></strong>
                                                <?php if($rights == 1): ?>
                                                    <i>(<?=$child['sort']?>)</i>
                                                <?php endif; ?>
                                                <p><?=$child['description']?></p>                                    
                                                <?php endif; ?>
                                                </div>    
                                            <? endforeach; endif; ?>
                                        </div>
                                           
                                    </div>
                                    <?php endif; ?> 
                                <? endforeach; ?>
                                
                            </div>
