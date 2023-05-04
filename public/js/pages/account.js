const imageUpload = document.getElementById("imageUpload");

const displayErrors = (errors = {}, form = null, containerSelector = null) => {
  let container;
  if (containerSelector !== null) {
    container = form?.querySelector(containerSelector);
  } else {
    container = document.createElement("div");
    container.id = "errorContainer";
    container.classList = "text-danger";
    form
      .querySelector('input[type="file"]')
      .insertAdjacentElement("afterend", container);
  }

  container.innerHTML = "";

  for (const key in errors) {
    if (errors.hasOwnProperty(key)) {
      const err = document.createElement("p");
      err.classList = "text-danger";
      err.innerHTML = `${key}: ${errors[key]}`;
      container.insertAdjacentElement("beforeend", err);
    }
  }
  return;
};

imageUpload.addEventListener("change", (e) => {
  if (e.target.files.length > 0) {
    return new ImagePreviewer(
      e,
      {
        maxFileSize: 10,
        errorsContainer: "#errorsContainer",
        title: "Prévisualiser votre photo de profil",
      },
      document.querySelector('[name="imageUploadForm"]')
    );
  }

  return (document.getElementById("errorsContainer").insertAdjacentHTML =
    '<p class="previewer-error item text-danger">Aucune image n\'a été chargée !</p>');
});

const handleChangeImageProfile = async (e) => {
  e.preventDefault();
  const form = e.target;
  const url = form.action;
  const method = form.method;
  const formData = new FormData(form);
  const headers = {};

  const res = await fetch(url, {
    method,
    headers,
    body: formData,
  });

  try {
    const data = await res.json();
    if (res.ok) {
      if (res.status === 200) {
        return (window.location = window.location.href);
      }
      form.querySelector('[data-bs-dismiss="modal"]').click();
    }
    return displayErrors(data?.violations, form);
  } catch (e) {
    console.error(e);
  }
};
