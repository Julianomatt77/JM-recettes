"use strict";
/**************
 * VARIABLES
 ******************/

//Définition des produits
const PRODUIT = [
  {
    name: "Croquettes Pedigree",
    categorie: "aliment",
    type: "croquette",
    animal: "chat",
    marque: "pedigree",
    age: "adulte",
    image: "images/produit-1/01.jpg",
    price: "9,99",
  },
  {
    name: "Divers Royal canin",
    categorie: "aliment",
    type: "divers",
    animal: "chien",
    marque: "royal_canin",
    age: "senior",
    image: "images/produit-2/02.jpg",
    price: "15,99",
  },
  {
    name: "Pâté Almo nature",
    categorie: "aliment",
    type: "pate",
    animal: "chat",
    marque: "autreMarque",
    age: "adulte",
    image: "images/produit-4/04-1.jpg",
    price: "4,99",
  },
  {
    name: "Croquettes chien adulte Purina",
    categorie: "aliment",
    type: "croquette",
    animal: "chien",
    marque: "autreMarque",
    age: "adulte",
    image: "images/produit-5/05-1.jpg",
    price: "14,99",
  },
  {
    name: "Croquettes puppy medium",
    categorie: "aliment",
    type: "croquette",
    animal: "chien",
    marque: "royal_canin",
    age: "bebe",
    image: "images/produit-6/06-1.jpg",
    price: "10,99",
  },
  {
    name: "Croquettes puppy maxi",
    categorie: "aliment",
    type: "croquette",
    animal: "chien",
    marque: "royal_canin",
    age: "bebe",
    image: "images/produit-7/07-1.jpg",
    price: "11,99",
  },
];

let storeProducts = document.querySelector("#store-products");

const BALISES = {};
BALISES.aliments = document.querySelector("#allType");
BALISES.type = document.querySelectorAll(".filterCheckAliment");

BALISES.marqueAll = document.querySelector("#marqueAll");
BALISES.marque = document.querySelectorAll(".filterCheckMarque");

BALISES.ageAll = document.querySelector("#ageAll");
BALISES.age = document.querySelectorAll(".filterCheckAge");

let val = [];

/***************************
 * ****FONCTIONS
 ****************************/

//Ajout des produits dans HTML
function addProduct() {
  for (let i = 0; i < PRODUIT.length; i++) {
    let index;
    storeProducts.innerHTML += `<div class="col-md-3 store-product ${PRODUIT[i].categorie} ${PRODUIT[i].type} ${PRODUIT[i].marque} ${PRODUIT[i].age} ${PRODUIT[i].animal} mt-3">
            <div class=" border mx-1 py-4"> 
            
                <div class="text-center"> 
                    <img src="${PRODUIT[i].image}" class="img-fluid" width="200"> 
                </div>

                <div class="about text-center mt-4">
                    <h5>${PRODUIT[i].name}</h5> <span>${PRODUIT[i].price} €</span>
                </div>

                <div class="cart-button mt-3 px-2 d-flex justify-content-between align-items-center"> 
                    <button class="btn btn-primary text-uppercase">Add to cart</button>
                </div>
            </div>
        </div>`;
  }
}

//Sélection/Désélection des types
function select(selectorAll, selector) {
  pushAllValues(selector);

  BALISES[selector].forEach((input, index) => {
    //Uncheck ALL TYPES while clicking on 1 categorie
    input.addEventListener("click", function () {
      BALISES[selectorAll].checked = false;
      getFilterValue(input.value, this.checked);
      hideProducts(input.value);
    });
  });

  //On sélectionne tous LES TYPES
  BALISES[selectorAll].addEventListener("click", function () {
    BALISES[selector].forEach((input) => {
      input.checked = true;
      getFilterValue(selector, this.checked, true);
      hideProducts(input.value);
    });
  });
}

//Ajouter toutes les valeurs du filtree dans la table val
function pushAllValues(selector) {
  for (let i = 0; i < BALISES[selector].length; i++) {
    let value = BALISES[selector][i].value;

    if (val.indexOf(value) == -1) {
      val.push(value);
    }
  }
}

//Update du tableau val en fonction des checkbox
function getFilterValue(value, check, isAll = false) {
  if (isAll) {
    pushAllValues(value);
  } else {
    if (check) {
      val.push(value);
    } else {
      val.splice(val.indexOf(value), 1);
    }
  }
}

//On masque les produit si les class de PRODUIT ne sont pas dans val.
function hideProducts(input) {
  let storeProduct = document.querySelectorAll(".store-product");
  for (let i = 0; i < storeProduct.length; i++) {
    if (
      val.includes(input) == false &&
      storeProduct[i].classList.contains(input) == true
    ) {
      storeProduct[i].classList.add("hide");
    } else if (
      val.includes(input) == true &&
      storeProduct[i].classList.contains(input) == true
    ) {
      storeProduct[i].classList.remove("hide");
    }
  }
}

/*************************************
 * Code principal
 **********************************/

//Filtre page boutique
document.addEventListener("DOMContentLoaded", function () {
  addProduct();
  select("aliments", "type");
  select("marqueAll", "marque");
  select("ageAll", "age");
});
