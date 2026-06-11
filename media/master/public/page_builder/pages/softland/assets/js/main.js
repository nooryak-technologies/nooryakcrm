"use strict";

window.onload = function () {
	window.setTimeout(fadeout, 500);
};

function fadeout() {
	var preloader = document.querySelector(".preloader");
	if (preloader) {
		preloader.style.opacity = "0";
		preloader.style.display = "none";
	}
}

window.onscroll = function () {
	var header_navbar = document.querySelector(".navbar-area");
	if (header_navbar) {
		if (window.pageYOffset > header_navbar.offsetTop) {
			header_navbar.classList.add("sticky");
		} else {
			header_navbar.classList.remove("sticky");
		}
	}
};

function onScroll(event) {
	var sections = document.querySelectorAll(".page-scroll");
	var scrollPos =
		window.pageYOffset ||
		document.documentElement.scrollTop ||
		document.body.scrollTop;
	for (var i = 0; i < sections.length; i++) {
		var currLink = sections[i];
		var val = currLink.getAttribute("href");
		var refElement = document.querySelector(val);
		var scrollTopMinus = scrollPos + 73;
		if (
			refElement &&
			refElement.offsetTop <= scrollTopMinus &&
			refElement.offsetTop + refElement.offsetHeight > scrollTopMinus
		) {
			const firstPageScroll = document.querySelector(".page-scroll");
			if (firstPageScroll) {
				firstPageScroll.classList.remove("active");
			}
			currLink.classList.add("active");
		} else {
			currLink.classList.remove("active");
		}
	}
}
window.document.addEventListener("scroll", onScroll);

let navbarToggler = document.querySelector(".navbar-toggler");
var navbarCollapse = document.querySelector(".navbar-collapse");
const pageScrollLinks = document.querySelectorAll(".page-scroll");
if (navbarToggler && navbarCollapse) {
	pageScrollLinks.forEach((e) =>
		e.addEventListener("click", () => {
			navbarToggler.classList.remove("active");
			navbarCollapse.classList.remove("show");
		})
	);
	navbarToggler.addEventListener("click", function () {
		navbarToggler.classList.toggle("active");
	});
}

if (typeof GLightbox === 'function') {
	try {
		GLightbox({
			href: "assets/video/video.mp4",
			type: "video",
			source: "local",
			width: 900,
			autoplayVideos: true,
		});
	} catch(e) {}
}

if (document.querySelector(".testimonial-active") && typeof tns === 'function') {
	try {
		new tns({
			container: ".testimonial-active",
			items: 2,
			slideBy: "page",
			autoplay: false,
			mouseDrag: true,
			gutter: 0,
			nav: true,
			controls: false,
			responsive: {0: {items: 1}, 992: {items: 2}},
		});
	} catch(e) {}
}

if (typeof WOW === 'function') {
	try { new WOW().init(); } catch(e) {}
}
