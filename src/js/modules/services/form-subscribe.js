const subscribe = function() {
    const forms = document.querySelectorAll('.subscribe-block__form, .widget-subscribe__form');
    const message = document.getElementById('subscribe-message');
    const messageTitle = message ? message.querySelector('.subscribe-block__message-title') : null;
    const messageText = message ? message.querySelector('.subscribe-block__message-text') : null;
    const closeBtn = document.getElementById('subscribe-message-close');

    if (!forms.length || !message || !messageTitle || !messageText) {
        return;
    }

    const hideMessage = function() {
        message.classList.remove('subscribe-block__message--visible');
    };

    if (closeBtn) {
        closeBtn.addEventListener('click', hideMessage);
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            hideMessage();
        }
    });

    const showMessage = function(params) {
        const title = params.title;
        const text = params.text;
        const isSuccess = params.isSuccess;
        messageTitle.textContent = title;
        messageText.textContent = text;
        message.classList.remove(
            'subscribe-block__message--success',
            'subscribe-block__message--error',
            'subscribe-block__message--visible'
        );
        message.classList.add(
            isSuccess ? 'subscribe-block__message--success' : 'subscribe-block__message--error',
            'subscribe-block__message--visible'
        );

        setTimeout(hideMessage, 3500);
    };

    forms.forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const data = new FormData(form);

            const nonceField = form.querySelector('input[name="subscribe_nonce"]');
            const nonce = nonceField ? nonceField.value : '';
            const honeypot = data.get('subscribe_hp') || '';

            fetch(ajax_object.ajaxurl, {
                method: 'POST',
                body: new URLSearchParams({
                    action: 'subscribe_user',
                    name: data.get('name') || '',
                    email: data.get('email') || '',
                    nonce: nonce,
                    subscribe_hp: honeypot
                })
            })
                .then(function(response) {
                    return response.json();
                })
                .then(function(result) {
                    showMessage({
                        title: result.success ? 'Подписка успешно оформлена!' : 'Ошибка',
                        text: result.data || (result.success ? 'Спасибо за подписку!' : 'Попробуйте повторить позднее.'),
                        isSuccess: Boolean(result.success)
                    });

                    if (result.success) {
                        form.reset();
                    }
                })
                .catch(function() {
                    showMessage({
                        title: 'Ошибка',
                        text: 'Не удалось отправить форму. Попробуйте ещё раз позже.',
                        isSuccess: false
                    });
                });
        });
    });
};

export default subscribe;