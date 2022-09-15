"use strict";
/**************
 * VARIABLES
 ******************/

const PRODUIT = [
  {
    name: "Croquettes Pedigree",
    categorie: "aliment",
    type: "croquette",
    animal: "chat",
    marque: "pedigree",
    age: "adulte",
    image: "images/produit-1/01.jpg",
    price: "9.99",
    quantite: "",
  },
  {
    name: "Divers Royal canin",
    categorie: "aliment",
    type: "divers",
    animal: "chien",
    marque: "royal_canin",
    age: "senior",
    image: "images/produit-2/02.jpg",
    price: "15.99",
    quantite: "",
  },
  {
    name: "Pâté Almo nature",
    categorie: "aliment",
    type: "pate",
    animal: "chat",
    marque: "autreMarque",
    age: "adulte",
    image: "images/produit-4/04-1.jpg",
    price: "4.99",
    quantite: "",
  },
];

let productQty;
let productTotal;
let qty = [];
let total = [];
let order;

/***************************
 * ****FONCTIONS
 ****************************/
//Ajout des produits dans HTML
function addProducts() {
  for (let i = 0; i < PRODUIT.length; i++) {
    document.querySelector(
      "#order"
    ).innerHTML += `<div class="row mb-1 py-2 yellowFont orderProduct text-center">
            <div class="col-lg-2 col-2 cartProduct">
              <img
                src=${PRODUIT[i].image}
                class="img-fluid h-100"
                alt="${PRODUIT[i].name}"
              />
            </div>
            <div class="col-lg-3 col-2 cartProduct">
              ${PRODUIT[i].name} <i class="bi bi-trash ms-5"></i>
            </div>
            <div class="col-lg-2 col-2 cartProduct">2 à 3 jours</div>
            <div class="col-lg-2 col-2 cartProduct">${PRODUIT[i].price}€</div>
            <div class="col-lg-1 col-2 cartProduct">
              <select
                class="form-select qty"
                aria-label="Default select example"
              >
                <option selected value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>
              </select>
            </div>
            <div class="col-lg-1 col-2 cartProduct productTotal"></div>
            <div class="col-lg-1 col-1 cartProduct"></div>
          </div>`;
  }
  productQty = document.querySelectorAll(".qty");
  productTotal = document.querySelectorAll(".productTotal");
  updateQty();
}

//Update de la quantité selon l'option choisi et mise à jour du prix par article et prix total du panier
function updateQty() {
  qty = [];
  for (let i = 0; i < productQty.length; i++) {
    let value = productQty[i].value;
    qty.push(value);
  }
  updateProductTotal();
  updateOrderPrice();
}

//Fonction de maj du prix par article
function updateProductTotal() {
  total = [];
  for (let i = 0; i < PRODUIT.length; i++) {
    let values = parseFloat(PRODUIT[i].price) * parseFloat(qty[i]);
    values = values.toFixed(2); //Arrondi le prix à 2 décimales

    total.push(values);

    //Insertion dans le HTML du prix maj
    productTotal[i].innerHTML = `${total[i]} €`;
  }
}

//Fonction de maj du prix total du panier
function updateOrderPrice() {
  order = 0;
  for (let i = 0; i < PRODUIT.length; i++) {
    order = order + parseFloat(total[i]);
  }

  //Insertion dans le html
  document.querySelector("#totalOrderPrice p").innerHTML = `Total = ${order} €`;
}

/*************************************
 * Code principal
 **********************************/

document.addEventListener("DOMContentLoaded", function () {
  addProducts();

  //maj lors du changement de la quantité
  productQty.forEach((item) => {
    item.addEventListener("change", updateQty);
  });
});
