// Global AJAX setup
$.ajaxSetup({
  data: { ajax: "1" }
});

class Ccc_Request {
  constructor() {
    this.form = null;
    this.url = null;
    this.method = null;
    this.methodDefault = "POST";
    this.params = {};
    this.useType = null;
    this.promptValue = null;
    this.modeOptions = { form: "form", url: "url" };
    this.statusTypes = { success: "success", failure: "failure" };
    this.showLoader = true;
    this.loader = null;
  }

  getAction() { return new Ccc_Action(); }
  getMessage() { return new Ccc_Message(); }
  getElement() { return new Ccc_Element(); }
  getLoader() {
    if (!this.loader) this.loader = new Ccc_Loader();
    return this.loader;
  }

  setShowLoader(showLoader) { this.showLoader = showLoader; return this; }
  canShowLoader() { return this.showLoader; }

  setTarget(elements) { 
    try { this.getLoader().presetLoader(elements); } 
    catch (err) { console.error("Loader preset failed:", err); }
    return this; 
  }

  setMode(useType) { this.useType = useType; this.resetAjaxParams(); return this; }
  getMode() { return this.useType; }

  setForm(element) {
    try {
      element = $(element);
      if (!element.length) throw new Error("form element is not set.");
      this.form = element;
      this.setMode(this.modeOptions.form);
      this.prepareAjaxParams();
    } catch (err) {
      console.error("Error setting form:", err);
    }
    return this;
  }

  getForm() { return this.form; }
  setUrl(url) { this.url = url; return this; }
  getUrl() { return this.url; }
  setPromptValue(promptValue) { this.promptValue = promptValue; return this; }
  getPromptValue() { return this.promptValue; }
  setMethod(method) { this.method = method; return this; }
  getMethod() { return this.method ?? this.methodDefault; }

  resetParams() { this.params = {}; return this; }
  setParams(params) { this.params = params; return this; }
  getParams() { return this.params; }

  addParam(key, val) { this.params[key] = val; return this; }
  removeParam(key) { if (key in this.params) delete this.params[key]; return this; }

  load(options = {}) {
    try {
      if (!this.validateAjaxParams()) {
        alert("Form Url is not valid.");
        return false;
      }
      if (this.getPromptValue()) {
        this.addParam("prompt_value", this.getPromptValue());
      }

      $.ajax({
        url: this.getUrl(),
        method: this.getMethod(),
        data: this.getParams(),
        async: true,
        beforeSend: () => { 
          try { if (this.canShowLoader()) { /* this.getLoader().show(); */ } } 
          catch (err) { console.warn("Loader beforeSend failed:", err); }
        },
        success: (response) => {
          try {
            if (typeof response === "string") {
              response = JSON.parse(response);
            }
            this.getMessage().setResponse(response).execute();
            this.getElement().setResponse(response).execute();
            this.getAction().setResponse(response).execute();
          } catch (err) {
            console.error("Error handling AJAX success:", err);
          }
        },
        error: (xhr, status, error) => console.error("AJAX error", status, error),
        complete: () => {
          try { /* this.getLoader().hide(); */ } 
          catch (err) { console.warn("Loader hide failed:", err); }
        }
      });
    } catch (err) {
      console.error("AJAX load failed:", err);
    }
    return this;
  }

  resetAjaxParams() { this.setMethod(null).setUrl(null).setParams({}); return this; }

  prepareAjaxParams() {
    try {
      if (this.getMode() !== this.modeOptions.form) {
        this.setMode(this.modeOptions.url);
        return false;
      }
      this.setUrl(this.getForm().attr("action"));
      this.setMethod(this.getForm().attr("method"));
      this.setParams(this.getForm().serialize());
      return true;
    } catch (err) {
      console.error("Error preparing AJAX params:", err);
      return false;
    }
  }

  validateAjaxParams() {
    return typeof this.getMethod() !== 'undefined' && typeof this.getUrl() !== 'undefined';
  }
}

class Ccc_Loader {
  constructor() { this.element = null; }
  getElement() {
    try {
      if (!this.element) {
        const id = `loader-${Math.floor(Math.random() * 100000)}`;
        this.element = document.createElement("div");
        $(this.element).attr("id", id).addClass("loader-core").text(`loading....${id}`);
        $("body").prepend(this.element);
      }
      return this.element;
    } catch (err) {
      console.error("Error creating loader element:", err);
      return $("<div>Loader Error</div>");
    }
  }
  show() { try { /* $(this.getElement()).show(); */ } catch(e){} return this; }
  hide() { try { /* $(this.getElement()).hide(); */ } catch(e){} }
  presetLoader(elements) {
    try {
      const loaderHtml = $("#loader").html();
      elements.forEach(el => {
        if ($(el).length) $(el).html(loaderHtml);
      });
    } catch (err) {
      console.error("Error presetting loader:", err);
    }
    return this;
  }
}

class Ccc_Message {
  constructor() {
    this.types = { alert: "alert", confirm: "confirm", prompt: "prompt" };
    this.promptValue = null;
    this.response = null;
  }

  getMessage() { return new Ccc_Message(); }
  getElement() { return new Ccc_Element(); }
  getAction() { return new Ccc_Action(); }
  getRequest() { return new Ccc_Request(); }

  setResponse(response) { this.response = response; return this; }
  getResponse() { return this.response; }

  execute() {
    try {
      const message = this.getResponse()?.message;
      if (typeof message !== "object") return false;

      switch (message.type) {
        case this.types.alert:  this.alert(message); break;
        case this.types.confirm: this.confirm(message); break;
        case this.types.prompt:  this.prompt(message); break;
        default: console.warn("Unknown message type:", message.type);
      }
    } catch (err) {
      console.error("Error executing message:", err);
    }
  }

  alert(message) {
    try {
      if (!message?.text) throw new Error("Alert message text missing.");
      alert(message.text);
    } catch (err) {
      console.error("Alert failed:", err);
    }
  }

  confirm(message) {
    try {
      if (!message?.text) throw new Error("Confirm message text missing.");
      const confirmed = confirm(message.text);

      if (confirmed && typeof message.confirm?.yes === "object") {
        try {
          this.getElement().setResponse(message.confirm.yes).execute();
          this.getMessage().setResponse(message.confirm.yes).execute();
          this.getAction().setResponse(message.confirm.yes).execute();
        } catch (err) {
          console.error("Error in confirm YES branch:", err);
        }
      } else if (!confirmed && typeof message.confirm?.no === "object") {
        try {
          this.getElement().setResponse(message.confirm.no).execute();
          this.getMessage().setResponse(message.confirm.no).execute();
          this.getAction().setResponse(message.confirm.no).execute();
        } catch (err) {
          console.error("Error in confirm NO branch:", err);
        }
      }
    } catch (err) {
      console.error("Confirm failed:", err);
    }
  }

  prompt(message) {
    try {
      if (!message?.text) throw new Error("Prompt message text missing.");
      const value = prompt(message.text);

      if (value != null && typeof message.prompt?.yes === "object") {
        try {
          this.setPromptValue(value);
          this.getElement().setResponse(message.prompt.yes).execute();
          this.getMessage().setResponse(message.prompt.yes).execute();
          this.getAction().setPromptValue(value).setResponse(message.prompt.yes).execute();
        } catch (err) {
          console.error("Error handling prompt YES branch:", err);
        }
      }
    } catch (err) {
      console.error("Prompt failed:", err);
    }
    return this;
  }

  setPromptValue(val) { this.promptValue = val; return this; }
  getPromptValue() { return this.promptValue; }
}

class Ccc_Action {
  constructor() {
    this.response = null;
    this.promptValue = null;
    this.actionType = { ajax: "ajax", location: "location", blank: "blank" };
  }

  getMessage() { return new Ccc_Message(); }
  getElement() { return new Ccc_Element(); }
  getRequest() { return new Ccc_Request(); }

  setPromptValue(val) { this.promptValue = val; return this; }
  getPromptValue() { return this.promptValue; }
  setResponse(response) { this.response = response; return this; }
  getResponse() { return this.response; }

  execute() { try { this.manageAction(); } catch(err){ console.error("Action execute failed:", err); } }

  manageAction() {
    const response = this.getResponse();
    if (typeof response?.action !== "object") return false;
    try {
      if (typeof response.action.url === "string") {
        this.manageActionFeature(response.action);
      } else {
        $(response.action).each((_, obj) => this.manageActionFeature(obj));
      }
    } catch (err) {
      console.error("Error managing action:", err);
    }
  }

  manageActionFeature(action) {
    try {
      if (typeof action !== "object" || typeof action.url !== "string") return;

      if (action.type === this.actionType.location) {
        window.location = action.url;
      } else if (action.type === this.actionType.blank) {
        window.open(action.url, "_blank");
      } else {
        const request = this.getRequest();
        if (this.getPromptValue()) request.setPromptValue(this.getPromptValue());

        if (typeof action.selector === "string") {
          request.getLoader().presetLoader([action.selector]);
        } else if (Array.isArray(action.selector)) {
          request.getLoader().presetLoader(action.selector);
        }
        action.loader = action.loader === false ? false : true;
        request.setShowLoader(action.loader).setUrl(action.url).load();
      }
    } catch (err) {
      console.error("Error in manageActionFeature:", err);
    }
  }
}

class Ccc_Element {
  constructor() { this.response = null; }
  getElement() { return new Ccc_Element(); }
  getAction() { return new Ccc_Action(); }
  getMessage() { return new Ccc_Message(); }
  getRequest() { return new Ccc_Request(); }

  setResponse(response) { this.response = response; return this; }
  getResponse() { return this.response; }
  execute() { try { this.manageElement(); } catch(err){ console.error("Element execute failed:", err); } }

  manageElement() {
    const response = this.getResponse();
    if (typeof response?.element !== "object") return;
    try {
      if (typeof response.element.selector === "string") {
        this.elementPosition(response.element);
      } else {
        $(response.element).each((_, obj) => this.elementPosition(obj));
      }
    } catch (err) {
      console.error("Error managing element:", err);
    }
  }

  elementPosition(object) {
    try {
      const currentElement = $(object.selector);
      if (!currentElement.length) {
        console.warn(`"${object.selector}" element not available in document.`);
        return this;
      }
      if (object.html !== undefined) {
        let htmlContent = typeof object.html === "object" ? JSON.stringify(object.html) : object.html;
        currentElement.html(htmlContent);
      }
      this.elementClass(object, currentElement);
      this.elementAttribute(object, currentElement);
      this.elementTrigger(object, currentElement);
      this.elementDisplay(object, currentElement);

      this.getMessage().setResponse(object).execute();
      this.getElement().setResponse(object).execute();
      this.getAction().setResponse(object).execute();
    } catch (err) {
      console.error("elementPosition error:", err);
    }
  }

  elementDisplay(object, currentElement) {
    try {
      if (object.show === 1 || object.show === true || object.show === "1") {
        currentElement.show();
        if (!isNaN(object.timer) && object.timer > 0) {
          setTimeout(() => currentElement.hide(), 1000 * object.timer);
        }
      } else if (object.show === 0 || object.show === false || object.show === "0") {
        currentElement.hide();
        if (!isNaN(object.timer) && object.timer > 0) {
          setTimeout(() => currentElement.show(), 1000 * object.timer);
        }
      }
      if (object.remove === 1) currentElement.remove();
    } catch (err) {
      console.error("elementDisplay error:", err);
    }
  }

  elementClass(object, currentElement) {
    try {
      if (typeof object.class !== "object") return this;
      if (typeof object.class.add === "string") currentElement.addClass(object.class.add);
      else if (Array.isArray(object.class.add)) object.class.add.forEach(cls => cls && currentElement.addClass(cls));
      if (typeof object.class.remove === "string") currentElement.removeClass(object.class.remove);
      else if (Array.isArray(object.class.remove)) object.class.remove.forEach(cls => cls && currentElement.removeClass(cls));
    } catch (err) {
      console.error("elementClass error:", err);
    }
  }

  elementAttribute(object, currentElement) {
    try {
      if (typeof object.attribute !== "object") return this;
      if (Array.isArray(object.attribute.add)) {
        object.attribute.add.forEach(attribute => {
          if (attribute?.name && attribute.value !== undefined) {
            currentElement.attr(attribute.name, attribute.value);
          }
        });
      }
      if (typeof object.attribute.remove === "string") currentElement.removeAttr(object.attribute.remove);
      else if (Array.isArray(object.attribute.remove)) object.attribute.remove.forEach(attr => currentElement.removeAttr(attr));
    } catch (err) {
      console.error("elementAttribute error:", err);
    }
  }

  elementTrigger(object, currentElement) {
    try {
      if (object.trigger === undefined) return this;
      if (Array.isArray(object.trigger)) object.trigger.forEach(event => currentElement.trigger(event));
      else if (typeof object.trigger === "string") currentElement.trigger(object.trigger);
    } catch (err) {
      console.error("elementTrigger error:", err);
    }
  }
}
