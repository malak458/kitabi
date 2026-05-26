document.addEventListener("DOMContentLoaded", function () {
 
  const bouton = document.createElement("button");
  bouton.id = "notif-btn";
  bouton.type = "button"; 
  bouton.textContent = "Sera Notifié";
  
  const wrapper = document.createElement("div");
  wrapper.id = "header-btns";

 
  const createRoom = document.getElementById("create_room");

  if (createRoom) {
   
    createRoom.parentNode.insertBefore(wrapper, createRoom);

   
    wrapper.appendChild(bouton);
    wrapper.appendChild(createRoom);
  }

  
  const style = document.createElement("style");
  style.textContent = `
    #header-btns { 
      display: flex; 
      gap: 10px; 
      align-items: center; 
      position: relative; 
      z-index: 999; 
    }
    #notif-btn {
      padding: 10px 20px;
      border-radius: 12px;
      border: 2px solid white;
      background: transparent;
      color: white;
      font-family: 'DM Sans', sans-serif;
      font-weight: 600;
      cursor: pointer;
      transition: 0.3s;
      position: relative;
      z-index: 1000;
    }
    #notif-btn:hover, #notif-btn.actif {
      background: white;
      color: #3b5d50;
    }
  `;
  document.head.appendChild(style);

  
  let actif = false;
  bouton.addEventListener("click", function (e) {
    e.preventDefault(); 
    e.stopPropagation(); 
    
    actif = !actif;
    if (actif) {
      this.textContent = "Notifié !";
      this.classList.add("actif");
    } else {
      this.textContent = "Sera Notifié";
      this.classList.remove("actif");
    }
    console.log("Clic détecté !"); 
  });
});