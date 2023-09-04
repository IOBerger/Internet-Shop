<fieldset class="order-field">
    <legend>Будем рады вашему заказу!</legend>
    <form id="order-form" method="post" name="order">
        <div class="main">
            <div class="form-row">
                <label for="product">
                    Товар
                </label>
                <p><?=$product['name']?></p>
                <input type="hidden" id="product" name="product" value="<?=$product['id']?>">     
            </div>
            <div class="form-row">
                <label for="comment">
                    Комментарий
                </label>
                <textarea id="comment" name="comment" required></textarea>
            </div>
            <div class="form-row">
                <input class="submit-button" type="submit" value="Отправить">
            </div> 
        </div>
    </form>
</fieldset>
