const imageUpload = document.getElementById("imageUpload");

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
  const headers = {
    // "Content-Type": "multipart/form-data;charset=utf-8; boundary=" + Math.random().toString().slice(0,2),
  };

  const res = await fetch(url, {
    method,
    headers,
    body: formData,
  });

  //   try {
  //     if (res.ok) {
  //       if (res.status === 201) {
  //       }
  //     }
  //   } catch (e) {
  //     console.error(e);
  //   }

  // return form.querySelector('[data-bs-dismiss="modal"]').click();
};
