    <fieldset class="order-field">
        <legend>Добавьте товар</legend>
        <form 
            <?php if($_GET['action']=='edit'): ?>
                id="edit-product-form" name="edit-product"
            <?php else: ?>
                id="add-product-form" name="add-product"
            <?php endif; ?>
            method="post" 
        >
            <div class="main">
                <input type="hidden" id="product-id" name="product-id" value="<?= $productInfo['id'] ?>" required="required">
                <div class="form-row">
                    <label for="product">
                        Название товара
                    </label>
                    <input type="text" id="product" name="product" value="<?= $productInfo['name'] ?>" required="required">
                </div>
                <div class="form-row">
                    <label for="product">
                        Между продуктами
                    </label>
                    
                    После
                    <input type="text" id="product-after" class="product-before-after" name="product-after" value="<?= !empty($productInfo['after']) ? $productInfo['after'] : '' ?>">
                    Перед
                    <input type="text" id="product-before" class="product-before-after" name="product-before" value="<?= !empty($productInfo['before']) ? $productInfo['before'] : '' ?>">
                </div>
                <div class="form-row">
                    <label for="category">
                        Размер
                    </label>
                    <select id="category" name="category" required="required">

                    <?php foreach($categories as $item): ?>
                        <option    
                            <?php if($productInfo['category']==$item['id']): ?>
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
                    <label for="color">
                        Цвет
                    </label>
                    <input type="text" id="color" name="color" value="<?= $productInfo['color'] ?>">
                </div>
                <div class="form-row">
                    <label for="size">
                        Размер
                    </label>
                    <select id="size" name="size" required="required">
                        <option value="XS" 
                            <?php if($productInfo['size']=='XS'): ?>
                                selected 
                            <?php endif; ?>>
                                XS
                        </option>
                        <option value="S" 
                            <?php if($productInfo['size']=='S'): ?>
                                selected 
                            <?php endif; ?>>
                                S
                        </option>
                        <option value="M" 
                            <?php if($productInfo['size']=='M'): ?>
                                selected 
                            <?php endif; ?>>
                                M
                        </option>
                        <option value="L" 
                            <?php if($productInfo['size']=='L'): ?>
                                selected 
                            <?php endif; ?>>
                                L
                        </option>
                        <option value="XL" 
                            <?php if($productInfo['size']=='XL'): ?>
                                selected 
                            <?php endif; ?>>
                                XL
                        </option>
                    </select>
                </div>
                <div class="form-row">
                    <label for="price">
                        Цена в долларах
                    </label>
                    <input type="text" id="price" name="price" value="<?= $productInfo['price'] ?>" required="required">
                </div>
                <div class="form-row">
                    <label for="annotation">
                        Описание
                    </label>
                    <textarea id="annotation" name="annotation"><?=$productInfo['annotation']?></textarea>
                </div>
                <div class="form-row">
                    <input class="submit-button" type="submit" value="Отправить">
                </div> 
            </div>
        </form>
    </fieldset>