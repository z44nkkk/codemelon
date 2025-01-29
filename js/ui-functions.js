let nav = document.querySelector('nav');
function toggleSection(objetiveSectionId, specialScrollTarget = false) {
  activeSection = document.querySelector('section[active]');
  activeNavButton = nav.querySelector('nav button[active]');
  if (activeSection.id === objetiveSectionId) {
    if(specialScrollTarget){
      document.querySelector(specialScrollTarget).scrollTo({
        top: 0,
        behavior: 'smooth'
      });
      return;
    }

    activeSection.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
    return;
  }

  // if (!document.startViewTransition) {
  //   updateDom(objetiveSectionId);
  //   return;
  // }

  // const transition = document.startViewTransition(() => {
    // updateDom(objetiveSectionId)
  // });
  // transition.finished
  updateDom(objetiveSectionId);
  function updateDom( objetiveSectionId ) {
    if(activeSection) {activeSection.removeAttribute('active'); activeSection.classList.remove("section-open");}
    if(activeNavButton) {activeNavButton.removeAttribute('active');}
    if(document.getElementById(objetiveSectionId)) {
      document.getElementById(objetiveSectionId).setAttribute('active', '');
      nav.querySelector(`button[data-section="${objetiveSectionId}"]`).setAttribute('active', '');
      document.getElementById(objetiveSectionId).classList.add("section-open");

      if((window.location.pathname).split("/").pop() === "home"){
        localStorage.setItem("currentSection", objetiveSectionId);

      }
    }
  }
}
function resetDialog(dialog){
  if(!dialog){return;}
  const inputs = dialog.querySelectorAll('input, textarea, select, '+ materialT("input"));
  for (let i=0; i<inputs.length; i++){
    inputs[i].value = "";
    inputs[i].removeAttribute('error');
  }
  toggleButton("#"+dialog.id, false);
}
function toggleDialog(dialogId) {
  if (dialogId == '' || dialogId == undefined){
    const openDialog = document.querySelector('md-dialog[open]')
    if(openDialog){
      openDialog.removeAttribute('open');
      openDialog.classList.remove('dialog-active');
      resetDialog(openDialog);
    }
    // resetForm();
    return
  }
  const dialog = document.getElementById(dialogId);
  dialog.setAttribute('open', '');
  dialog.classList.add('dialog-active');

}

// Menus
function toggleMenu(menuId, originButton = false) {
  if(originButton){
    const menu = originButton.nextElementSibling;
    menu.open = !menu.open;
    return;
  }

  const menu = document.getElementById(menuId);
  menu.open = !menu.open;
}


// old
function materialT(elements) {
  const mapping = {
    'option': 'md-select-option',
    'select': 'MD-OUTLINED-SELECT, MD-FILLED-SELECT, md-outlined-select, md-filled-select',
    'select-not-reset': 'md-outlined-select:not(.no-reset), md-filled-select:not(.no-reset)', 
    'button': 'md-outlined-button, md-filled-button, md-filled-tonal-button, md-text-button, md-elevated-button',
    'input': 'md-outlined-text-field, md-filled-text-field',
    'input-not-reset': 'md-outlined-text-field:not(.no-reset), md-filled-text-field:not(.no-reset)',
    'slider': 'md-slider',
    'textarea': 'mwc-textarea'
  };

  const elementList = elements.split(',').map(e => e.trim().toLowerCase());

  const result = [];
  elementList.forEach(element => {
    const mapped = mapping[element];
    if (mapped) {
      result.push(mapped);
    }
  });

  return result.length > 0 ? result.join(', ') : 'Componente no mapeado';
}

function resetForm(parent){
  if (parent) {
    const parentElement = document.querySelector(parent);
    if(!parentElement){return;}
    var inputs = parentElement.querySelectorAll(materialT("input-not-reset")+', textarea, select:not(.no-reset), input:not(.no-reset) ,'+materialT("select-not-reset")+','+materialT("slider"));
  } else {
    var inputs = document.querySelectorAll(materialT("input")+', textarea, select:not(.no-reset), input:not(.no-reset)');
  }
  for (let i=0; i<inputs.length; i++){
    inputs[i].value = "";
    inputs[i].style.background = "";
    inputs[i].classList.remove('error');
  }
}

function resetFormNextGen(parentId){
  if(!parent){return;}
  const parentElement = document.getElementById(parentId);
  if(!parentElement){return;}
  const inputs = parentElement.querySelectorAll(materialT("input-not-reset")+', textarea, select:not(.no-reset), input:not(.no-reset) ,'+materialT("select-not-reset")+','+materialT("slider"));
  for (let i=0; i<inputs.length; i++){
      if(inputs[i].tagName == "select" || inputs[i].tagName == "MD-OUTLINED-SELECT" || inputs[i].tagName == "MD-FILLED-SELECT"){
        inputs[i].querySelectorAll(materialT("option")).forEach(element => {
          element.selected = false;
        });
      }else{
        if(inputs[i].tagName == "MD-SLIDER"){
          inputs[i].value = 50;
        }else{
          inputs[i].value = "";
        }
      }
      
    inputs[i].removeAttribute('error');
    try{inputs[i].reportValidity()} catch(e){}
    inputs[i].style.background = "";
    inputs[i].classList.remove('error');
  }
}

function checkEmpty(parentId, elementToCheck){
  const parentElement = document.querySelector(parentId);
  if(!parentElement){return;}
  const inputs = parentElement.querySelectorAll(`${materialT(elementToCheck)}, ${elementToCheck}`);
  validation = 0;
  for (let i=0; i<inputs.length; i++){
    inputs[i].addEventListener("focus", function() {inputs[i].removeAttribute('error')}, {once: true});
    if(inputs[i].value === "" || inputs[i].value === "0"){ 
      validation = 1; 
      inputs[i].setAttribute('error', '');;
    }
  }
  if(validation != 0){
    // if(type==="dialog"){toggleWindow("#empty_spaces")} 
    return false;
  }else{
    return true
  }
}

function toggleButton(parentId, state, type){
  const parentElement = document.querySelector(parentId);
  if(!parentElement){return;}
  lastButton = parentElement.querySelector(materialT("button"));
  if(type === "submit"){lastButton = parentElement.querySelector('[type="submit"]')}
  if(state){
    lastButton.disabled = true;
  } else {
    lastButton.disabled = false;
  }
}

let currentTimeoutId = null;

function message(message, action){
  const messageElement = document.querySelector("MESSAGE");
  if (action === "error") {messageElement.classList.add('error');}
  if (action === "success") {messageElement.classList.add('success'); }
  
  messageElement.innerHTML = message;
  messageElement.style.display = "flex";
  messageElement.style.animation = "messageIn 0.7s cubic-bezier(0.6, -0.14, 0.02, 1.29)";
  if (currentTimeoutId) {clearTimeout(currentTimeoutId);}
  currentTimeoutId = setTimeout(() => {
      messageElement.style.animation = "messageOut 0.8s";
      setTimeout(() => {
        messageElement.style.display = "none"; 
        currentTimeoutId = null;
        messageElement.className="";
      }, 700);
  }, 4000);
}
function toggleWindowFullSize(){
  if(!document.querySelector('transparent window.active')){return;}

  state = Flip.getState("transparent window.active");
  windowId = document.querySelector('transparent window.active').id;
  document.getElementById(windowId).classList.toggle('full-size');

  timeline = Flip.from(state, {
    // ease: CustomEase.create("custom", "M0,0 C0.308,0.19 0.107,0.633 0.288,0.866 0.382,0.987 0.656,1 1,1 "),
    ease: CustomEase.create("easeName", "0.38,0.49,0,1"),
    targets: "window.active",
    duration: 0.7,
    scale:true,
    simple:true,
  })
  timeline.play();
}
function toggleWindow(windowId, position, scale, appearStyle = false){
  if (windowId == ''){windowId = null}

  const windowNew = document.querySelector(windowId);
  if(windowNew){
    transparent = windowNew.closest('transparent');
  }else{
    windowActive = document.querySelector("window.active");
    if(!windowActive){return;}
    transparent = windowActive.closest('transparent');
  }


  if (transparent.hasAttribute('data-beautiful_transparent')) {
    transparent.removeAttribute('data-beautiful_transparent');
  }

  // Close any other open window
  
  const activeWindow = transparent.querySelector('window.active');

  function closingAnimation() {
    if (transparent.hasAttribute("closing")) {
      transparent.classList.remove('active');
      transparent.removeAttribute("closing");
      
      activeWindow.classList.remove('active');
    }
  }

  if (activeWindow) {
    if (transparent.hasAttribute("closing")) { return; }
    toggleOvermessage();

    // This attribute added and all makes the close animation smooth
    transparent.setAttribute("closing", "");
    transparent.addEventListener("animationend", () =>{closingAnimation()}, {once: true})
    
    resetFormNextGen(activeWindow.id)
    // resetForm();
    return;
  }
  if (transparent.hasAttribute("closing") && transparent.classList.contains("active")) {
    transparent.removeAttribute("closing");
  }

  // remove useless classes
  transparent.classList.remove('dynamic', 'right', 'left', 'top', 'bottom');


  // Window to open
  if (!windowNew) { return; }
  transparent.classList.add('active'); 
  localStorage.setItem("currentWindow", windowId); 

  

  // Set origin element of animation
  if (event && event.currentTarget) {
    element = event.currentTarget;
    windowNew.classList.remove("not-animated");
  }else{
    element = null
    windowNew.classList.add("not-animated");
  }
  // if(originElement){
  //   element = document.getElementById(originElement);
  // }

  // specific functions per window
  switch (windowId) {
    case "#window-create_movement": 
      setInputDate();
    break;
    case "#window-account": 
      getUserData()
    break;
    case "#window-settings":
      // displaySettings();  
      break;
    case "#window-register-calories":

      setDateTime("register-calories-date" , "register-calories-time"); 
      break;
    default: break;
  }

  // Set element with Dynamic position
  if(position == "absolute"){
    windowNew.classList.add("absolute");
    var rect = element.getBoundingClientRect();
    screenWidth = window.innerWidth;
    screenHeight = window.innerHeight;
    // Tests
    
    

    if (rect.left < (screenWidth/2)) {
      windowNew.style.right = "unset";
      windowNew.style.left = Math.round(rect.left)+"px";
      transparent.classList.add("left");
    } else{
      windowNew.style.left = "unset";
      windowNew.style.right = screenWidth-Math.round(rect.right)+"px";
      transparent.classList.add("right");
    }

    if (rect.top < (screenHeight/2)) {
      windowNew.style.bottom = "unset";
      windowNew.style.top = (Math.round(rect.top) + Math.round(rect.height) + 8)+"px";
      if(appearStyle){
        windowNew.style.top = (Math.round(rect.top))+"px";
      }
      transparent.classList.add("top");

    }else{
      windowNew.style.top = "unset";
      windowNew.style.bottom = (screenHeight-Math.round(rect.bottom) + Math.round(rect.height) + 8)+"px";
      if(appearStyle){
        windowNew.style.bottom = (screenHeight-Math.round(rect.bottom))+"px";
      }
      transparent.classList.add("bottom");
    }
    
    
    // requestAnimationFrame(function() {
    //   var windowHeight = windowNew.offsetHeight;
    //   var windowBottom = screenHeight - (windowNew.offsetTop + windowNew.offsetHeight);
      
    //   var windowWidth = windowNew.offsetWidth;

    // });
  }
  if(scale === undefined){scale = 0}else{scale = 1}
  animate(element, windowNew, position, scale);
}
function animate(element, windowNew, position, scale){
  let easeType = CustomEase.create("custom", "M0,0 C0.308,0.19 0.107,0.633 0.288,0.866 0.382,0.987 0.656,1 1,1 ");
  if(position === "absolute" && window.innerWidth >= 681){
    easeType = CustomEase.create("custom", "M0,0 C0.249,-0.124 0.04,0.951 0.335,1 0.684,1.057 0.614,0.964 1,1");
    // easeType = CustomEase.create("custom", "M0,0 C0.311,0 0.118,0.629 0.319,0.872 0.457,1.039 0.818,1.001 1,1 ");
    
    // easeType = CustomEase.create("custom", "M0,0 C0.249,-0.124 -0.003,0.896 0.325,1.044 0.653,1.191 0.585,0.935 1,1 ");
    // easeType = CustomEase.create("custom", "M0,0 C0.249,-0.124 0.026,0.939 0.335,1.013 0.685,1.097 0.585,0.935 1,1 ");
    
    // easeType = CustomEase.create("custom", "M0,0 C0.249,-0.124 0.045,0.925 0.335,1 0.625,1.074 0.532,0.987 1,1");
  }

  if (scale === 0 || window.innerWidth >= 681) {
    var scaleValue = true;
  }else{
    var scaleValue = false;
  }


  
  let state = Flip.getState(element);
  windowNew.classList.toggle('active');
  Flip.from(state, {
    targets: windowNew,
    duration: 0.7,
    scale: scaleValue,
    ease: easeType,
    // ease: CustomEase.create("custom", "M0,0 C0.154,0 0.165,0.541 0.324,0.861 0.532,1.281 0.524,1 1,1 "),
    // ease: CustomEase.create("custom", "M0,0 C0.154,0 0.18,0.666 0.35,0.861 0.562,1.106 0.611,1 1,1 "),
    // ease: CustomEase.create("custom", "M0,0 C0.308,0.19 0.107,0.633 0.288,0.866 0.382,0.987 0.656,1 1,1 "),
    // ease: CustomEase.create("easeName", ".47,.29,0,1"),
    // ease: CustomEase.create("easeName", ".58,.18,0,1"),
    // ease: CustomEase.create("easeName", ".21,.19,0,1"),
    // ease: CustomEase.create("emphasized", "0.2, 0, 0, 1"),
    // ease: CustomEase.create("classic", "0.1, 0.8, 0, 1"),
    // ease: CustomEase.create("classic", "0.4, 0.4, 0, 1.2"),
    // ease: CustomEase.create("custom", "M0,0 C0.099,0 0.133,0.915 0.325,1.044 0.642,1.257 0.64,0.938 1,1 "),
    // ease: CustomEase.create("custom", "M0,0 C0.249,-0.124 -0.003,0.896 0.325,1.044 0.653,1.191 0.585,0.935 1,1 "),
    absolute: true,
  })
    
}


function currencySymbol() {
  const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
  if (timezone.includes('America')) {
    return '$';
  }
  return '€';
}

function formatMoney(amount) {
  // Get user timezone to approximately determine region
  const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
  
  // Default to EUR
  let currency = 'EUR';
  
  // Check if timezone is in Americas
  if (timezone.includes('America')) {
    currency = 'USD';
  }

  return new Intl.NumberFormat('en-US', { 
    style: 'currency', 
    currency: currency 
  }).format(amount);
}
function dateToText(date, showYear) {
  if(showYear === undefined){showYear = false}
  const months = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
  const [year, month, day] = date.split("-");
  if(showYear){
    return `${parseInt(day)} de ${months[month - 1]} de ${year}`;
  }
  return `${parseInt(day)} de ${months[month - 1]}`;
}
function dateToPrettyDate(date, showYear) {
  if(date === "0000-00-00"){return "-";}
  if(showYear === undefined){showYear = false}
  const [year, month, day] = date.split("-");
  if(showYear){
    return `${parseInt(day)}/${month}/${year}`;
  }
  return `${parseInt(day)}/${month}`;
}

function dateToShort(date, showYear = false) {
  if (!date) return '';
  const shortMonths = ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"];
  const [year, month, day] = date.split("-");
  if(showYear){
    return `${parseInt(day)} ${shortMonths[parseInt(month) - 1]} ${year}`;
  }
  return `${parseInt(day)} ${shortMonths[parseInt(month) - 1]}`;
}

function timeToAmPm(time) {
  if (!time) return '';
  const [hours, minutes] = time.split(':');
  let hour = parseInt(hours);
  const ampm = hour >= 12 ? 'PM' : 'AM';
  hour = hour % 12;
  hour = hour ? hour : 12; // convert 0 to 12
  return `${hour}:${minutes} ${ampm}`;
}

function formatTime(time){
  if(time === undefined){return;}
  if(time === "00:00:00"){return "-";}
  const [hours, minutes, seconds] = time.split(":");
  return `${hours}:${minutes}`;
}

// function toggleTab(windowId, tabId, workHidden){
//   const windowElement = document.getElementById(windowId);
//   const currentActiveTab = windowElement.querySelector('.md-tab[active]');
//   if (currentActiveTab) {currentActiveTab.removeAttribute('active');}


//   if(workHidden){
//     const currentActiveTabSelector = windowElement.querySelector('md-tabs [active]');
//     if (currentActiveTabSelector) {currentActiveTabSelector.removeAttribute('active');}
//     const objetiveTabSelector = windowElement.querySelector('[data-tab-id="'+tabId+'"]');
//     objetiveTabSelector.setAttribute('active', '');
//   }

  

//   const objetiveTab = windowElement.querySelector('.md-tab[id="'+tabId+'"]');
//   objetiveTab.setAttribute('active', '');
  
// }
function toggleMdTab(origin = false, tabId){
  var desiredPanel = false;
  if(origin){
    const desiredPanelId = origin.getAttribute("aria-controls");
    desiredPanel = document.getElementById(desiredPanelId);
  }
  if(!origin && tabId != undefined){
    desiredPanel = document.getElementById(tabId);
  }
  // if(!origin){
  //   const currentActiveTab = document.querySelector("md-tab[active]");
  // }
  // falta por trabajar
  
  if(!desiredPanel) return;
  
  const currentPanel = desiredPanel.parentElement.querySelector("[data-md-panel][active]");
  if(currentPanel) {
    currentPanel.removeAttribute("active");
  }
  desiredPanel.setAttribute("active", "");
}

// function addTableRow(tableId, templateId){
//   const table = document.getElementById(tableId);
//   const template = document.getElementById(templateId);

// }
function applyAnimation(state, target, scale = true, absolute = false, customEase = false, zIndex = false){
  easeToUse = CustomEase.create("custom", "M0,0 C0.308,0.19 0.107,0.633 0.288,0.866 0.382,0.987 0.656,1 1,1 ")
  if(!zIndex){zIndex = 0}else{zIndex = 100}
  if(customEase){easeToUse = CustomEase.create("easeName", "0.38,0.49,0,1")}
  let timeline = Flip.from(state, {
    ease: easeToUse,
    // ease: CustomEase.create("custom", "M0,0 C0.154,0 0.165,0.541 0.324,0.861 0.532,1.281 0.524,1 1,1 "),
    targets: target,
    duration: 0.7,
    absolute:absolute,
    scale:scale,
    zIndex:zIndex,
    simple:true
  })
  timeline.play();
}

function removeTableRow(row = false){
  if(!row){return;}
  const parentTable = row.closest("table");
  rowsState = Flip.getState(`#${parentTable.id} tr`);
  row.remove();
  let timeline = Flip.from(rowsState, {
      ease: CustomEase.create("custom", "M0,0 C0.308,0.19 0.107,0.633 0.288,0.866 0.382,0.987 0.656,1 1,1 "),
      targets: `#${parentTable.id} tr`,
      absolute:false,
      scale:true,
      simple:true,
  })
  timeline.play();
  countTableRows(parentTable.id);
}

function countTableRows(tableId){
  const table = document.getElementById(tableId);
  const rowsCount = table.querySelectorAll('tr').length;
  
  if(rowsCount <= 1){
    table.querySelector("tr").remove();
    table.parentElement.querySelector(".container-info-empty-table").innerHTML = `
      <div class="content-box on-background-text align-center info-table-empty">
        <md-icon class="pretty medium">sentiment_content</md-icon>
        <span class="headline-small">No hay registros</span>
      </div>
    `;
  }
}

function setDateTime(dateParent, timeParent){
  const date = getDate();
  const time = getTime();

  const dateParentElement = document.getElementById(dateParent);
  const timeParentElement = document.getElementById(timeParent);

  if(dateParentElement){
    dateParentElement.value = `${date.year}-${date.month}-${date.day}`;
  }
  if(timeParentElement){
    timeParentElement.value = `${time.hours}:${time.minutes}`;
  }
}

function getDate(){
  const date = new Date();

  const year = date.getFullYear();
  const month = String(date.getMonth() + 1).padStart(2, '0'); // Los meses van de 0 a 11
  const day = String(date.getDate()).padStart(2, '0');

  const response = {
    "year": year,
    "month": month,
    "day": day,
  }
  return response;
}
function getTime(){
  const date = new Date();

  const hours = String(date.getHours()).padStart(2, '0');
  const minutes = String(date.getMinutes()).padStart(2, '0');

  const response = {
    "hours": hours,
    "minutes": minutes,
  }
  return response;
}

function toggleWSection(wSectionId, originButton){
  if (wSectionId.charAt(0) === '#') {
    wSectionId = wSectionId.substring(1);
  }
  if(originButton === undefined){
    holder = document.getElementById(wSectionId).closest("HOLDER");
  }else{
    holder = originButton.closest("HOLDER");
  }
  const activeWSection = holder.querySelector('.w-section[active]');
  const activeWSectionButton = holder.querySelector('button[active]');
  if(wSectionId === activeWSection.id){return false;}

  const objetiveWSection = holder.querySelector(`#${wSectionId}`);
  const objetiveWSectionButton = holder.querySelector(`button[data-w-section="${wSectionId}"]`);

  const activeWSectionParent = activeWSection.parentElement;
  const objetiveWSectionParent = objetiveWSection.parentElement;
  // objetiveWSectionParent.style.background = "blue";

  // objetiveWSection.style.background = "red";


  // activeWSectionParent.style.background = "red";
  // objetiveWSectionParent.style.background = "blue";


  if(activeWSectionParent !== objetiveWSectionParent){

    var innerActiveWSectionButton = objetiveWSectionParent.querySelector('button[active]');
    var innerActiveWSection = objetiveWSectionParent.querySelector('.w-section[active]');

    if(innerActiveWSectionButton){ innerActiveWSectionButton.removeAttribute('active');}
    if(innerActiveWSection){ innerActiveWSection.removeAttribute('active');}

    objetiveWSection.setAttribute('active', '');
    objetiveWSectionButton.setAttribute('active', '');
    return true;
  }


  if(objetiveWSection){
    if(activeWSection){activeWSection.removeAttribute('active');}
    if(activeWSectionButton){activeWSectionButton.removeAttribute('active');}
    objetiveWSection.setAttribute('active', '');
    if(objetiveWSectionButton){objetiveWSectionButton.setAttribute('active', '');}
  }
  return true;
}

function changeWindow(windowId){
  toggleWindow();
  setTimeout(function() {
    toggleWindow(windowId);
  },250);
}


function toggleOvermessage(overId){
  if (overId == ''){overId = null}

  // const currentWindow = document.querySelector(getStorage("currentWindow")); 
  const currentWindow = document.querySelector('transparent window.active');

  // Close
  const activeOvermessage = currentWindow.querySelector(".overmessage.active");
  function closingAnimation() {
    if (activeOvermessage.hasAttribute("closing")) {
      activeOvermessage.classList.remove('active');
      activeOvermessage.removeAttribute("closing");
    }
  }
  if (activeOvermessage) {
    activeOvermessage.setAttribute("closing", "");
    activeOvermessage.addEventListener("animationend", () =>{closingAnimation()}, {once: true})
    return;
  }
  if (activeOvermessage) {
    if (activeOvermessage.hasAttribute("closing") && activeOvermessage.classList.contains("active")) {
      activeOvermessage.removeAttribute("closing");
    }
  }
  

  // Open
  const overmessage = currentWindow.querySelector(overId);
  if(!overmessage){ return; }
  overmessage.classList.add("active");

}



function toggleSubSection(subSectionId, options = {exclusive: false}){
  const subSection = document.querySelector(subSectionId);
  
  const mainParent = subSection.parentElement;
  var activeContent = mainParent.querySelectorAll(':scope > *:not([data-sub-section]:not([active]))');
  activeContent = Array.from(activeContent).filter(element => element !== subSection);
  // console.log(activeContent);
  
  
  // const desireSubSectionState = Flip.getState(subSection);
  // const eventOriginState = Flip.getState(event.currentTarget);

  // If we try to open the already active subsection, dont do anything
  if(subSection.hasAttribute("active") && options.action === "open") return false;

  if(options.animationType) {
    if(options.animationType === "from-origin"){
      var eventOriginState = Flip.getState(event.currentTarget);
      if(options.customOrigin){
        eventOriginState = Flip.getState(options.customOrigin);
      }

      subSection.toggleAttribute("active");
      subSection.setAttribute("sub-section-simple-in-animation", "");
      
      Flip.from(eventOriginState, {
        ease: CustomEase.create("custom", "M0,0 C0.308,0.19 0.107,0.633 0.288,0.866 0.382,0.987 0.656,1 1,1 "),
        // ease: CustomEase.create("easeName", "0.38,0.49,0,1"),
        targets: subSection,
        duration: 0.7,
        toggleClass: "apply-blur-animation-2",
        scale:true,
        onComplete: () => {subSection.removeAttribute("sub-section-simple-in-animation")}
      }).play();

      
      
    }
    // console.log("hello")
    return;
  }
  

  if(subSection.hasAttribute('active')){
    
    subSection.toggleAttribute('sub-section-in-animation-out');
    subSection.addEventListener("animationend", () => { 
      subSection.removeAttribute('sub-section-in-animation-out');

      const activeContentState = Flip.getState(activeContent);
      subSection.removeAttribute('active');

       Flip.from(activeContentState, {
          // ease: CustomEase.create("custom", "M0,0 C0.308,0.19 0.107,0.633 0.288,0.866 0.382,0.987 0.656,1 1,1 "),
          ease: CustomEase.create("easeName", "0.38,0.49,0,1"),
          // ease: CustomEase.create("custom", "M0,0 C0.249,-0.124 0.04,0.951 0.335,1 0.684,1.057 0.614,0.964 1,1"),
          // ease: CustomEase.create("custom", "M0,0 C0.298,0 0.261,0.696 0.419,0.91 0.575,1.121 0.736,0.972 1,1 "),
          // targets: subSections,
          duration: .3,
          // scale:true,
          // toggleClass: "apply-blur-animation-2",
          simple:true,
          absolute:true,
        }).play();

    }, {once: true});
  }else{
    // Open the subsection
    subSection.toggleAttribute('active');
    subSection.toggleAttribute('sub-section-in-animation');
    subSection.addEventListener("animationend", () => { 
      subSection.removeAttribute('sub-section-in-animation');
    }, {once: true});
  }

  

  


  



  // Flip.from(activeContentState, {
  //   // ease: CustomEase.create("custom", "M0,0 C0.308,0.19 0.107,0.633 0.288,0.866 0.382,0.987 0.656,1 1,1 "),
  //   // ease: CustomEase.create("easeName", "0.38,0.49,0,1"),
  //   ease: CustomEase.create("custom", "M0,0 C0.249,-0.124 0.04,0.951 0.335,1 0.684,1.057 0.614,0.964 1,1"),
  //   // ease: CustomEase.create("custom", "M0,0 C0.298,0 0.261,0.696 0.419,0.91 0.575,1.121 0.736,0.972 1,1 "),
  //   // targets: subSections,
  //   duration: 1,
  //   scale:true,
  //   // toggleClass: "apply-blur-animation-2",
  //   simple:true,
  //   // absolute:true,
  // }).play();

  // Flip.from(eventOriginState, {
  //   ease: CustomEase.create("custom", "M0,0 C0.308,0.19 0.107,0.633 0.288,0.866 0.382,0.987 0.656,1 1,1 "),
  //   // ease: CustomEase.create("easeName", "0.38,0.49,0,1"),
  //   targets: subSection,
  //   duration: 0.7,
  //   toggleClass: "apply-blur-animation-2",
  //   scale:true,
  //   // simple:true,
  // }).play();

  if(options.exclusive) {
    // Close any other active subsections
    const activeSubSections = document.querySelectorAll(`[data-sub-section][active]`);
    activeSubSections.forEach(section => {
      if(section.id != subSection.id) {
        console.log("fucking closing")
        section.removeAttribute('active');
      }
    });
  }


}


function flowChilds(parent, options = {startOpacity: 0, animationVariant: "", betweenDelay: 0.05}){
  options.startOpacity = options.startOpacity || 0;
  options.animationVariant = options.animationVariant || "";
  options.betweenDelay = options.betweenDelay || 0.05;

  Array.from(parent.children).forEach((el, index) => {
      el.style.opacity = options.startOpacity;
      el.style.animationDelay = `${index * options.betweenDelay}s`; // Retraso de 0.2s por elemento
      el.classList.add(`search-result-item-in${options.animationVariant}`); // Agrega la clase que activa la animación
      el.addEventListener("animationend", () => {
          el.classList.remove(`search-result-item-in${options.animationVariant}`)
          el.style.opacity = "initial";
      }, {once: true})
  });
}


// function lazyLoad(){

// }