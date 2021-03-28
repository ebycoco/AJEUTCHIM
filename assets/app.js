/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import "./scss/app.scss";

// start the Stimulus application
import $ from "jquery";
import "bootstrap";
$(".custom-file-input").on("change", function (e) {
  var inputFile = e.currentTarget;
  $(inputFile)
    .parent()
    .find(".custom-file-label")
    .html(inputFile.files[0].name);
});
const ratio = 0.1;
const options = {
  root: null,
  rootMargin: "0px",
  threshold: ratio,
};

const handleIntersect = function (entries, observer) {
  entries.forEach(function (entry) {
    if (entry.intersectionRatio > ratio) {
      entry.target.classList.remove("reveal");
      observer.unobserve(entry.target);
    }
  });
};

document.documentElement.classList.add("reveal-loaded");
window.addEventListener("DOMContentLoaded", function () {
  const observer = new IntersectionObserver(handleIntersect, options);
  document.querySelectorAll(".reveal").forEach(function (r) {
    observer.observe(r);
  });
});
