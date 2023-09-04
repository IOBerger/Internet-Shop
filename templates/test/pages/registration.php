<fieldset class="order-field">
    <legend>Регистрация</legend>
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
    </fieldset>