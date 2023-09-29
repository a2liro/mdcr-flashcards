const handleCredentialResponse = function (obj) {
  if (!obj.credential) {
    alert("Não foi possível fazer login com sua conta do google");
    return;
  }

  const options = {
    method: "POST",
    // mode: 'cors',
    headers: {
      "Content-Type": "application/json",
      // 'Access-Control-Allow-Origin': '[::1]:8080',
      // 'X-Requested-With': 'XmlHttpRequest'
    },
    body: JSON.stringify(obj),
  };

  fetch("/login-google", options)
    .then((response) => response.text())
    .then((text) => {
      window.location.href = "/cursos";
    })
    .catch((error) => {
      console.log("Error: ", error);
    });
};

const googleSignIn = document.getElementById("googleSignIn");

window.addEventListener("load", function () {
  google.accounts.id.initialize({
    client_id:
      "487420919780-pepa0p9jjnafol5t7iahak0bqlfo4q07.apps.googleusercontent.com",
    callback: handleCredentialResponse,
  });

  googleSignIn.addEventListener("click", function () {
    let Cookies = document.cookie.split(";");
    for (let i = 0; i < Cookies.length; i++) {
      document.cookie =
        Cookies[i] + "=; expires=" + new Date(0).toUTCString();
    }
    document.cookie = "g_astate";
    google.accounts.id.prompt((notification) => {
      if (notification.isNotDisplayed() || notification.isSkippedMoment()) {
        console.log("carregando...");
        return;
      }
    });
  });
});
