const EmailManager = (() => {

    const sendEmailForm = document.getElementById("form-contact-us");
    const formName = sendEmailForm.querySelector("[name='name']");
    const formCompany = sendEmailForm.querySelector("[name='company']")
    const formPhone = sendEmailForm.querySelector("[name='phone']")
    const formEmail = sendEmailForm.querySelector("[name='email']")
    const formMessage = sendEmailForm.querySelector("[name='message']")

    async function sendEmail(){
        if(!checkEmpty(`#${sendEmailForm.id}`, 'input')){return;}
        toggleButton(`#${sendEmailForm.id}`, true)

        var templateParams = {
            name: formName.value,
            company: formCompany.value,
            phone: formPhone.value,
            email: formEmail.value,
            message: formMessage.value
        }
        await emailjs.send("service_w0ssvcp","template_e1vwdhn", templateParams).then(
            (response) => {
                message("¡Hemos recibido tu mensaje!", "success");
            },
            (error) => {
                message("¡Algo salió mal! Inténtalo de nuevo mas tarde.", "error");
                console.log('message failed', error);
            },
        );
        toggleButton(`#${sendEmailForm.id}`, false)
        toggleWindow();
    }

    function copyContactEmail(){
        const contactEmail = document.getElementById("copy-contact-email-value");
        const contactEmailValue = contactEmail.innerHTML;
        navigator.clipboard.writeText(contactEmailValue);
        message("¡Email copiado al portapapeles!", "success");
    }

    return {
        sendEmail: sendEmail,
        copyContactEmail: copyContactEmail
    };

})();

export default EmailManager;

//
// Desarrollado por Diego Josue Muñoz Muñoz
// GitHub: https://github.com/z44nkkk
// Basado en Stepbro Software: https://devbro.net
//