

async function signUp(ev){
  ev.preventDefault();
  const activeWindow = document.querySelector("window.active");
  const currentForm =  activeWindow.querySelector("form");
  const parent = "#window-sb-signup";
  if(!checkEmpty(parent, "input")) { return; }

  const userInput = document.getElementById("user-signup-email");
  const pwdInput = document.getElementById("user-signup-password");
  const pwdRepeatInput = document.getElementById("user-signup-password-repeat");

  if(pwdInput.value != pwdRepeatInput.value) {
    pwdInput.setAttribute("error", "");
    pwdRepeatInput.setAttribute("error", "");
    message("Las contraseñas no son iguales", "error");
    return;
  }

  pwdInput.removeAttribute("error");
  pwdRepeatInput.removeAttribute("error");

  const regex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
  if (!regex.test(userInput.value)) {
    message("El correo no es valido", "error");
    userInput.setAttribute("error", "");
    return false;
  }

  pwdInput.removeAttribute("error");
  pwdRepeatInput.removeAttribute("error");

  toggleButton(parent, true, "submit");

  const data = {
    op: "signup",
    email: userInput.value,
    pwd: pwdInput.value,
  };
  const url = `${BASE_URL}back-end/controllers/users.controller.php`;
  const response = await fetch(url, {
    method: 'POST',
    body: JSON.stringify(data),
  });
  if (response.ok) {
    toggleButton(parent, false, "submit");
    const result = await response.json();
    switch (result) {
      case "user_already_exists":
        message("El usuario ya existe", "error");
        currentForm.querySelector("[data-form-step-1]").scrollIntoView({ behavior: 'smooth' });
        userInput.setAttribute("error", "");
        break;
      case "access_accepted":
        window.location.href='home';
        break;
      default:
        message("Hubo un error", "error");
    }
  } else {
    console.error('Error al registrarse:', response.statusText);
  }
}

async function logIn(ev){
  ev.preventDefault();
  const activeWindow = document.querySelector("window.active");
  const currentForm =  activeWindow.querySelector("form");
  const parent = "#window-sb-login";
  if(!checkEmpty(parent, "input")) { return; }
  toggleButton(parent, true, "submit");

  const userInput = document.getElementById("user-login-email");
  const pwdInput = document.getElementById("user-login-password");

  const data = {
    op: "login",
    user: userInput.value,
    pwd: pwdInput.value,
  };
  const url = `${BASE_URL}back-end/controllers/users.controller.php`;
  const response = await fetch(url, {
    method: 'POST',
    body: JSON.stringify(data),
  });
  if (response.ok) {
    toggleButton(parent, false, "submit");
    const result = await response.json();
    switch (result) {
      case "user_doesnt_exist": 
        message("El usuario no existe", "error"); 
        currentForm.querySelector("[data-form-step-1]").scrollIntoView({ behavior: 'smooth' });
        userInput.setAttribute("error", ""); 
        break;
      case "wrong_password": 
        message("Usuario o contraseña incorrectos", "error"); 
        userInput.setAttribute("error", "");
        pwdInput.setAttribute("error", "");
        break;
      case "access_accepted":
        window.location.href='home';
        break;
    }
  } else {
    console.error('Error al iniciar sesión:', response.statusText);
  }
}

async function logOut(){
  const data = {
    op: "logout",
  };
  const url = `${BASE_URL}back-end/controllers/users.controller.php`;
  const response = await fetch(url, {
    method: 'POST',
    body: JSON.stringify(data),
  });
  if (response.ok) {
    const result = await response.json();
    if (result) { window.location.href='home';}
  } else {
    console.error('Error al cerrar sesión:', response.statusText);
  }
}

async function modifyUserData(){
  const parent = "#dialog-logout-confirmation"
  if(!checkEmpty(parent, "input")) { return; }
  toggleButton(parent, true)

  const username = document.getElementById("modify-account-username")
  const data = {
    op: "modifyUserData",
    name: username.value,
  };
  const url = `${BASE_URL}back-end/controllers/users.controller.php`;
  const response = await fetch(url, {
    method: 'POST',
    body: JSON.stringify(data),
  });
  if (response.ok) {
    const result = await response.json();
    toggleButton(parent, false);

    if(!result){
      message("Hubo un error", "error");
      return false;
    }
    if(result === "user_already_exists"){
      message("Nombre de usuario ya en uso", "error");
      username.setAttribute("error", "");
      return false;
    }
    message("Datos modificados", "success");
    syncUserData();
    // toggleWindow();
  }
}

async function getUserData(){
  const data = {
    op: "getUserData",
  };
  const url = `${BASE_URL}back-end/controllers/users.controller.php`;
  const response = await fetch(url, {
    method: 'POST',
    body: JSON.stringify(data),
  });
  if (response.ok) {
    const result = await response.json();
    if(!result){
      message("Hubo un error", "error");
      return false;
    }
    
    var id = result.id;
    var name = result.name;
    var email = result.email;

    return {id, name, email};

    // document.getElementById("response-account-id").textContent = id;
    // document.getElementById("response-account-email").textContent = email;
    // document.getElementById("modify-account-username").value = name;
  }
}

async function syncUserData(){
  const data = await getUserData();
  if(!data) { message("Error obteniendo datos del usuario"); return; }

  document.getElementById("response-account-id").textContent = data.id;
  document.getElementById("response-account-email").textContent = data.email;
  document.getElementById("modify-account-username").value = data.name;

  document.getElementById("response-settings-account-email").textContent = data.email;
  document.getElementById("response-settings-account-username").innerHTML = (data.name == "") ? "<i class='outline-text'>Sin nombre de usuario</i>" : data.name;
  document.getElementById("response-settings-account-username-title").textContent = data.name;

  const settingsProfileCircle = document.getElementById("response-settings-account-username-first-letter"); 
  if(settingsProfileCircle) {
    settingsProfileCircle.textContent = (data.name.charAt(0).toUpperCase() == "") ? data.email.charAt(0) : data.name.charAt(0).toUpperCase();
  }
  const headerProfileCircle = document.getElementById("response-header-account-username-first-letter"); 
  if(headerProfileCircle) {
    document.getElementById("response-header-account-username-first-letter").textContent = (data.name.charAt(0).toUpperCase() == "") ? data.email.charAt(0) : data.name.charAt(0).toUpperCase();
  }  
}


function validateFormStep1(){
  const activeWindow = document.querySelector("window.active");
  const currentForm =  activeWindow.querySelector("form");
  const emailField = currentForm.querySelector("input[name='user-email']");
  if(!checkEmpty(`#${currentForm.id} [data-form-step-1]`, "input")) { return; }
  if(!validateEmailData(emailField.value) && activeWindow.id == "window-sb-signup") {
    emailField.setAttribute("error", "");
    message("El correo no es valido", "error");
    return false;
  }
  emailField.removeAttribute("error");
  currentForm.classList.remove("overflow-hidden");
  currentForm.querySelector("[data-form-step-2] input").focus({ preventScroll: true });
  currentForm.querySelector("[data-form-step-2] input").scrollIntoView({ behavior: 'smooth' });
}

function validateEmailData(value){
  const regex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
  return regex.test(value);
}

function resetFormSteps(){
  const activeWindow = document.querySelector("window.active");
  const currentForm =  activeWindow.querySelector("form");
  currentForm.classList.add("overflow-hidden");
}


// new google auth
function handleCredentialResponse(response) { 
  // Post JWT token to server-side
  fetch(`${BASE_URL}back-end/controllers/users.controller.php`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ op: "google_auth", credential: response.credential }),
  })
  .then(response => response.json())
  .then(data => {
      if (data.status === 1) {
          // Redirigir o manejar el inicio de sesión exitoso
          window.location.href = 'home'; // O lo que necesites hacer
      } else {
          // Manejar errores
          console.error(data.message);
          message(data.message, "error");
      }
  })
  .catch(console.error);
}

//
// Desarrollado por Diego Josue Muñoz Muñoz
// GitHub: https://github.com/z44nkkk
// Basado en Stepbro Software: https://devbro.net
//