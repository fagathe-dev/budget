class ImagePreviewer {
  constructor(e, options = {}, form = null) {
    this.options = options;
    this.imageContainer = document.querySelector("img.previewer-image");
    this.previewImage(e);
  }

  enableSubmit() {
    if (this.hasErrors()) {
      form?.querySelector('[submit]').setAttribute('disabled', true);
    }
  }

  getTrustedImageTypes() {
    return ["jpg", "jpeg", "svg", "gif", "png", "avif", "webp", "apng"];
  }

  checkImage(image) {
    const extension = image.name.split(".").pop();
    if (!this.getTrustedImageTypes().includes(extension)) {
      this.errors = { 
        ...this.errors, 
        type: "Cette image n'est pas valide !" 
      };
    }
    this.checkImageSize(image);
  }

  previewImage(e) {
    const image = e.target.files[0];
    const node = e.target;
    this.errors = new Object();
    console.debug(this.errors)
    this.checkImage(image);
    this.displayErrorsMsg(node);

    return this.preview(image, node);
  }

  checkImageSize(image) {
    if (this.options.hasOwnProperty("maxFileSize")) {
      const allowedSize = this.convertToMb(this.options.maxFileSize);
      if (image.size > allowedSize) {
        this.errors = {
          ...this.errors,
          fileSize: "Ce fichier est trop volumineux !",
        };
      }
    }
  }

  hasErrors() {
    return Object.keys(this.errors).length > 0;
  }

  displayErrorsMsg(node) {
    let target = document.querySelector("#errorsContainer"),
      container,
      content = "";

    if (target !== null && target !== undefined) {
      container = target;
    } else if (
      this.options.hasOwnProperty("errorsContainer") &&
      (container === null || container === undefined)
    ) {
      container = document.querySelector(this.options.errorsContainer);
      if (container === null || container === undefined) {
        container = document.createElement("div");
        container.setAttribute("id", "errorsContainer");
        container.classList = "previewer-error list";
        node.insertAdjacentElement("afterend", container);
      }
    } else if (target !== null && target !== undefined) {
      container = target;
    }
    console.info(this.errors)
    if (this.hasErrors() === false) {
      container.innerHTML = "";
    }
    if (this.imageContainer !== undefined && this.imageContainer !== null) {
      this.imageContainer.remove();
    }

    for (const key in this.errors) {
      if (this.errors.hasOwnProperty(key)) {
        content += `<p class="previewer-error item text-danger">${key} : ${this.errors[key]}</p>`;
      }
    }
    console.warn({ content });

    return (container.innerHTML = content);
  }

  convertToMb(size = 0, type = "MB") {
    const types = ["B", "KB", "MB", "GB", "TB"];

    const key = types.indexOf(type.toUpperCase());

    if (typeof key !== "boolean") {
      return parseInt(size) * 1024 ** key;
    }
    return "invalid type: type must be GB/KB/MB etc.";
  }

  preview(image, node) {
    let imageContainer = this.imageContainer;
    if (this.imageContainer === undefined || this.imageContainer === null) {
      imageContainer = document.createElement("img");
      imageContainer.classList = "previewer-image";
      node.insertAdjacentElement("beforebegin", imageContainer);
      if (this.options.hasOwnProperty("title")) {
        imageContainer.setAttribute("alt", this.options.title);
        imageContainer.setAttribute("title", this.options.title);
      }
      this.imageContainer = imageContainer;
    }

    this.imageContainer.src = URL.createObjectURL(image);
    this.imageContainer.srcset = URL.createObjectURL(image);
    return (node.onload = () => URL.revokeObjectURL(this.imageContainer.src)); // Free memory
  }
}
