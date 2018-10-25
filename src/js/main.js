require("../css/main.css")
import locations from "./locations";
const isArray = require('lodash/isArray')

let map;
let markers = [];

const loader = `<svg width="100px"  height="100px"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" class="lds-magnify search__loader" style="background: none;"><g transform="translate(50,50)"><g ng-attr-transform="scale({{config.scale}})" transform="scale(0.8)"><g transform="translate(-50,-50)"><g transform="translate(10.2727 -20)"><animateTransform attributeName="transform" type="translate" calcMode="linear" values="-20 -20;20 -20;0 20;-20 -20" keyTimes="0;0.33;0.66;1" dur="1s" begin="0s" repeatCount="indefinite"></animateTransform><path ng-attr-fill="{{config.glass}}" d="M44.19,26.158c-4.817,0-9.345,1.876-12.751,5.282c-3.406,3.406-5.282,7.934-5.282,12.751 c0,4.817,1.876,9.345,5.282,12.751c3.406,3.406,7.934,5.282,12.751,5.282s9.345-1.876,12.751-5.282 c3.406-3.406,5.282-7.934,5.282-12.751c0-4.817-1.876-9.345-5.282-12.751C53.536,28.033,49.007,26.158,44.19,26.158z" fill="#dfdcf6"></path><path ng-attr-fill="{{config.frame}}" d="M78.712,72.492L67.593,61.373l-3.475-3.475c1.621-2.352,2.779-4.926,3.475-7.596c1.044-4.008,1.044-8.23,0-12.238 c-1.048-4.022-3.146-7.827-6.297-10.979C56.572,22.362,50.381,20,44.19,20C38,20,31.809,22.362,27.085,27.085 c-9.447,9.447-9.447,24.763,0,34.21C31.809,66.019,38,68.381,44.19,68.381c4.798,0,9.593-1.425,13.708-4.262l9.695,9.695 l4.899,4.899C73.351,79.571,74.476,80,75.602,80s2.251-0.429,3.11-1.288C80.429,76.994,80.429,74.209,78.712,72.492z M56.942,56.942 c-3.406,3.406-7.934,5.282-12.751,5.282s-9.345-1.876-12.751-5.282c-3.406-3.406-5.282-7.934-5.282-12.751 c0-4.817,1.876-9.345,5.282-12.751c3.406-3.406,7.934-5.282,12.751-5.282c4.817,0,9.345,1.876,12.751,5.282 c3.406,3.406,5.282,7.934,5.282,12.751C62.223,49.007,60.347,53.536,56.942,56.942z" fill="#50574d"></path></g></g></g></g></svg>`

function initMap(id) {

    const center = (locations[id]) ? { lat: locations[id].lat ,lng: locations[id].lng } : { lat: 42.374490 ,lng: -71.117185 } 

    map = new google.maps.Map(document.getElementById('map-canvas'), {
        center: center,
        zoom: 8
    });

    google.maps.event.addListenerOnce(map, "idle", configure);

}

function configure() {

    $(".ui-panel").css("opacity", "1");

    $("input[name='geo']").typeahead({
        minLength: 3,
        highlight: true
    },{
        display: function(suggestion) { return null; },
        limit: 5,
        source: search,
        templates: {
            suggestion: Handlebars.compile(
                '<a class="button is-fullwidth">' +
                '{{ place_name }}, {{ admin_name1 }}, {{ postal_code }}' +
                '</a>')
            ,
            notFound: Handlebars.compile(
                '<a class="button is-fullwidth" disabled>' +
                'No Results...' +
                '</a>')
        }
    })

    $("input[name='geo']").on("typeahead:selected", function(eventObject, suggestion, name) {
        map.setCenter({lat: parseFloat(suggestion.latitude), lng: parseFloat(suggestion.longitude)});
        update();
    })

    $('#form').on('submit', (e) => {
        e.preventDefault()

        $.ajax({
            method: "POST",
            url: '/articles',
            data: { query: $input.value },
            success: function(response) {
                console.log(response)
            }
        })
    })

    google.maps.event.addListener(map, "dragend", function() {
        update();
    });
    
    update();
}

function drawMarkers(places) {
    var largeInfowindow = new google.maps.InfoWindow();

    places.forEach((place) => {
        let marker = new google.maps.Marker({
            position: { lat: parseFloat(place.latitude), lng: parseFloat(place.longitude) },
            map: map,
            animation: google.maps.Animation.DROP,
            title: place.place_name
        });

        marker.addListener('click', function() {
            getArticles(this, largeInfowindow, place)
        });
        // console.log(marker)
        markers.push(marker)   
    })


}

function removeMarkers() {
    if(markers.length) {
        markers.forEach((marker) =>{
            marker.setMap(null);
        })
    }
}

function search(query, syncResults, asyncResults){
    $('.control').addClass('is-loading')
    
    $.ajax({
        method: "POST",
        url: '/search',
        data: { query: query },
        success: function(response) {
            // console.log(response)
            asyncResults(response)
            $('.control').removeClass('is-loading')
        }
    })        
}

function update() {
    let bounds = map.getBounds();

    let ne = bounds.getNorthEast();
    let sw = bounds.getSouthWest();

    let parameters = {
        ne: `${ne.lat()},${ne.lng()}`,
        sw: `${sw.lat()},${sw.lng()}`
    };

    $.ajax({
        method: "POST",
        url: '/update',
        data: parameters,
        success: function(response) {
            removeMarkers();
            drawMarkers(response)
        }
    })        
}

function getArticles(marker, infowindow, place) {
    $.ajax({
        method: "GET",
        url: '/articles',
        data: { 
            geo: place.postal_code, 
        },
        dataType: 'json',
        success: function(response) {
            // console.log(response)
            if(!isArray(response) && response.hasOwnProperty('pubDate')) {
                updateInfoWindow(marker, infowindow, place, [response]) 
            } else {
                updateInfoWindow(marker, infowindow, place, response) 
            }
            
        }
    })
    populateInfoWindow(marker, infowindow, place)  
}

function populateInfoWindow(marker, infowindow, place) {

    const _state = place.admin_name1.split(' ').join('-').toLowerCase();
    infowindow.setContent(
                        `
                            <div class="infowindow">
                                <div class="infowindow__header">
                                    <div class="infowindow__flag">
                                        <figure class="image is-16by9">
                                            <img src="https://civilserviceusa.github.io/us-states/images/flags/${_state}-small.png">
                                        </figure>
                                    </div>
                                    <div class="infowindow__details">
                                        <p>
                                            <strong>${place.place_name}</strong> ${place.admin_name1}
                                            <br>
                                            Latitude: ${place.latitude}
                                            <br>
                                            Longitude: ${place.longitude}
                                        </p>
                                    </div>                                    
                                </div>
                                <hr>
                                <div class="articles">
                                    ${loader}
                                </div>                                           
                            </div>
                        `
    )
    infowindow.open(map, marker)
}    


    function updateInfoWindow(marker, infowindow, place, response) {
        var articlesHtml
        if(typeof response !== 'string') {
            articlesHtml = `
            <div class="articles">
                <h4 class="articles__heading">
                    Articles for ${place.place_name}
                </h4>
                <ul class="articles__list">`
                    for(var i = 0; i < response.length; i++) {
                    articlesHtml += `
                        <li class="articles__list-item">
                            <a class="articles__link" href="${response[i].link}">
                                ${response[i].title}
                            </a>
                        </li>`
                        if(i >= 10) {
                            break;
                        }
                    }
                articlesHtml += 
                `</ul>
            </div`
        } else {
                articlesHtml = `
                <div class="articles">
                    <h4 class="articles__heading" style="text-align: center; padding-top: 2rem">
                        There are no news articles for ${place.place_name}
                    </h4>
                `
        }            
            const _state = place.admin_name1.split(' ').join('-').toLowerCase();
            infowindow.setContent(
                                `
                                <div class="infowindow">
                                    <div class="infowindow__header">
                                        <div class="infowindow__flag">
                                            <figure class="image is-16by9">
                                                <img src="https://civilserviceusa.github.io/us-states/images/flags/${_state}-small.png">
                                            </figure>
                                        </div>
                                        <div class="infowindow__details">
                                            <p>
                                                <strong>${place.place_name}</strong> ${place.admin_name1}
                                                <br>
                                                Latitude: ${place.latitude}
                                                <br>
                                                Longitude: ${place.longitude}
                                            </p>
                                        </div>                                    
                                    </div>
                                    <hr>
                                    ${articlesHtml}                                        
                                </div>
                                `
            )

        infowindow.open(map, marker)
    }      



const route = window.location.pathname

if(route === '/map') {
    const id = (window.location.search.length) ? window.location.search.split("=").pop() : null;
    initMap(id);
} else if(route === '/'){
    initDataVis() 
} else if(route === '/about') {
    initAbout()
}

function initDataVis() {

    var svg = d3.select("#statesvg");

    var path = d3.geoPath();

    d3.json("https://d3js.org/us-10m.v1.json").then(data => {

        svg.append("g")
        .attr("class", "states")
        .selectAll("path")
        .data(topojson.feature(data, data.objects.states).features)
        .enter().append("path")
            .attr("d", path)
            .attr("id", (d) => d.id)
            .on('click', function(e) {
                window.location.href = `http://mashup.tbullock.net/map?id=${this.id}`
            })

    })
    .catch(error => {
        console.log(error)
    })

}

function initAbout() {
    $('.message').css("opacity", "1");
}
