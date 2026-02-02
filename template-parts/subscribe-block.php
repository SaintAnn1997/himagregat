<section class="subscribe-block section-margins">
    <div class="container">

        <div class="subscribe-block__wrapper">
            <div class="subscribe-block__content">
                <h2 class="subscribe-block__content-title">Подпишитесь на наш журнал</h2>
                <p class="subscribe-block__content-text">Получайте актуальную информацию о химической
                    промышленности
                    ежемесячно</p>
            </div>

            <form class="subscribe-block__form">
                <div class="subscribe-block__form-data">
                    <div class="subscribe-block__form-inputs">
                        <input class="subscribe-block__form-input" name="name" type="text" placeholder="Ваше имя" autocomplete="name" required>
                        <input class="subscribe-block__form-input" name="email" type="email" placeholder="E-mail"
                            autocomplete="email" required>
                        <button class="subscribe-block__form-btn btn">Оформить подписку</button>
                    </div>
                    <div class="subscribe-block__checkbox">
                        <input type="checkbox" id="subscribe_consent" name="consent" required>
                        <label for="subscribe_consent">Нажимая кнопку, я даю <a href="<?php echo get_permalink(965); ?>" target="_blank">согласие на обработку моих персональных данных</a> в соответствии с <a href="<?php echo get_permalink(3); ?>" target="_blank">Политикой конфиденциальности</a> и принимаю её.</label>
                    </div>
                </div>
                <div class="subscribe-block__honeypot" aria-hidden="true">
                    <label class="subscribe-block__honeypot-label" for="subscribe_hp">Оставьте это поле пустым</label>
                    <input type="text" id="subscribe_hp" name="subscribe_hp" tabindex="-1" autocomplete="off">
                </div>
                <input type="hidden" name="subscribe_nonce" value="<?php echo esc_attr(wp_create_nonce('khag_subscribe_nonce')); ?>">
            </form>
        </div>

    </div>
</section>