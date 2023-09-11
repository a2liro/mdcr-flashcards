// /**
//   Renderiza o botão de login na tela
// */
// function renderButton() {
//     gapi.signin2.render('meu-botao', {
//         'scope': 'email profile https://www.googleapis.com/auth/plus.login', // solicitando acesso ao profile e ao e-mail do usuário
//         'width': 250,
//         'height': 50,
//         'longtitle': true,
//         'theme': 'dark',
//         'onsuccess': onSuccess,
//         'onfailure': onFailure
//     });
// }

// /**
//   Função executada quando o login é efetuado com sucesso
// */
// function onSuccess(googleUser) {
//     // Recuperando o profile do usuário
//     var profile = googleUser.getBasicProfile();
//     console.log("ID: " + profile.getId()); // Don't send this directly to your server!
//     console.log("Name: " + profile.getName());
//     console.log("Image URL: " + profile.getImageUrl());
//     console.log("Email: " + profile.getEmail());

//     // Recuperando o token do usuario. Essa informação você necessita passar para seu backend
//     var id_token = googleUser.getAuthResponse().id_token;
//     console.log("ID Token: " + id_token);
// }

// /**
//   Função executada quando ocorrer falha no logn
// */
// function onFailure(error) {
//     console.log(error);
// }

// /**
//   Função de deslogar o usuário
// */
// function signOut() {
//     var auth2 = gapi.auth2.getAuthInstance();
//     auth2.signOut().then(function () {
//         console.log('User signed out.');
//     });
// }

// const client = google.accounts.oauth2.initCodeClient({
//     client_id: '487420919780-pepa0p9jjnafol5t7iahak0bqlfo4q07.apps.googleusercontent.com',
//     scope: 'https://www.googleapis.com/auth/calendar.readonly',
//     ux_mode: 'popup',
//     callback: (response) => {
//       const xhr = new XMLHttpRequest();
//       xhr.open('POST', code_receiver_uri, true);
//       xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
//       // Set custom header for CRSF
//       xhr.setRequestHeader('X-Requested-With', 'XmlHttpRequest');
//       xhr.onload = function() {
//         console.log('Auth code response: ' + xhr.responseText);
//       };
//       xhr.send('code=' + response.code);
//     },
//   });

const handleCredentialResponse = function (obj) {
  if (!obj.credential) {
    alert("Não foi possível fazer login com sua conta do google");
    return;
  }

  const options = {
    method: 'POST',
    // mode: 'cors',
    headers: {
        'Content-Type': 'application/json',
        // 'Access-Control-Allow-Origin': '[::1]:8080',
        // 'X-Requested-With': 'XmlHttpRequest'
    },
    body: JSON.stringify(obj),
  }

  fetch('/login-google', options)
  .then((response) => response.text())
  .then((text) => {
    console.log(text);
    // window.location.href = '/cursos'
  })
  .catch((error) => {
    console.log('Error: ', error)
  })
};

const googleSignIn = document.getElementById("googleSignIn");

window.addEventListener("load", function () {
  google.accounts.id.initialize({
    client_id:
      "487420919780-pepa0p9jjnafol5t7iahak0bqlfo4q07.apps.googleusercontent.com",
    callback: handleCredentialResponse,
  });

  googleSignIn.addEventListener("click", function () {
    document.cookie = "g_astate";
    google.accounts.id.prompt((notification) => {
      if (notification.isNotDisplayed() || notification.isSkippedMoment()) {
        console.log("carregando...");
        return;
      }
    });
  });
});
