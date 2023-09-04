<?php if($rights==1): ?>
<fieldset class="category-field">
        <legend>Категория</legend>
        <form 
            <?php if($_GET['action']=='edit'): ?>
                id="edit-category-form" name="edit-category"
            <?php else: ?>
                id="add-category-form" name="add-category"
            <?php endif; ?>
            method="post" 
        >
            <div class="main">
                <input type="hidden" id="category-id" name="category-id" value="<?= $categoryInfo['id'] ?>" required="required">
                <div class="form-row">
                    <label for="title">
                        Название категории
                    </label>
                    <input type="text" id="title" name="title" value="<?= $categoryInfo['title'] ?>" required="required">
                </div>
                <div class="form-row">
                    <label for="title">
                        Сортировка
                    </label>
                    <input type="text" id="sort" name="sort" value="<?= $categoryInfo['sort'] ?>">
                </div>
                <div class="form-row">
                    <label for="parent">
                        Родительская категория
                    </label>
                    <select id="parent" name="parent">
                    <option value="0" selected>Верхняя категория</option>
                    <?php foreach($categoryList as $item): ?>
                        <option    
                            <?php if($categoryInfo['parent']==$item['id']): ?>
                                selected
                            <?php endif; ?>
                            value=<?=$item['id']?>
                        >
                            <?=$item['title']?>
                        </option>
                    <? endforeach; ?>      

                    </select>
                </div>
                <div class="form-row">
                    <label for="description">
                        Описание
                    </label>
                    <textarea id="description" name="description"><?=$categoryInfo['description']?></textarea>
                </div>
                <div class="form-row">
                    <input class="submit-button" type="submit" value="Отправить">
                </div> 
                <?php if($categoryInfo['status']==1): ?>
                    <div class="category-delete-wrp"><a id="category-delete" class="category-delete" data-id="<?=$categoryInfo['id']?>">Удалить</a></div>
                <?php else: ?>
                    <div class="category-ressurect-wrp"><a id="category-ressurect" class="category-ressurect" data-id="<?=$categoryInfo['id']?>">Восстановить</a></div>
                <?php endif; ?>
                <div>
                    <a class="category-delete-forever-button" data-id="<?=$categoryInfo['id']?>">Удалить НАВЕЧНО</a>
                </div>   
            </div>
        </form>
    </fieldset>
    <?php endif; ?>