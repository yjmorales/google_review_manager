<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Google\GoogleMap\Place\Services\PlaceDetails;

/**
 * Holds the Place Business Industry Types used by the Google Place API to retrieve information.
 *
 * @link: https://developers.google.com/maps/documentation/places/web-service/supported_types
 */
class PlaceBusinessIndustryTypes
{
    const accounting = 'accounting';
    const airport = 'airport';
    const amusement_park = 'amusement_park';
    const aquarium = 'aquarium';
    const art_gallery = 'art_gallery';
    const atm = 'atm';
    const bakery = 'bakery';
    const bank = 'bank';
    const bar = 'bar';
    const beauty_salon = 'beauty_salon';
    const bicycle_store = 'bicycle_store';
    const book_store = 'book_store';
    const bowling_alley = 'bowling_alley';
    const bus_station = 'bus_station';
    const cafe = 'cafe';
    const campground = 'campground';
    const car_dealer = 'car_dealer';
    const car_rental = 'car_rental';
    const car_repair = 'car_repair';
    const car_wash = 'car_wash';
    const casino = 'casino';
    const cemetery = 'cemetery';
    const church = 'church';
    const city_hall = 'city_hall';
    const clothing_store = 'clothing_store';
    const convenience_store = 'convenience_store';
    const courthouse = 'courthouse';
    const dentist = 'dentist';
    const department_store = 'department_store';
    const doctor = 'doctor';
    const drugstore = 'drugstore';
    const electrician = 'electrician';
    const electronics_store = 'electronics_store';
    const embassy = 'embassy';
    const fire_station = 'fire_station';
    const florist = 'florist';
    const funeral_home = 'funeral_home';
    const furniture_store = 'furniture_store';
    const gas_station = 'gas_station';
    const gym = 'gym';
    const hair_care = 'hair_care';
    const hardware_store = 'hardware_store';
    const hindu_temple = 'hindu_temple';
    const home_goods_store = 'home_goods_store';
    const hospital = 'hospital';
    const insurance_agency = 'insurance_agency';
    const jewelry_store = 'jewelry_store';
    const laundry = 'laundry';
    const lawyer = 'lawyer';
    const library = 'library';
    const light_rail_station = 'light_rail_station';
    const liquor_store = 'liquor_store';
    const local_government_office = 'local_government_office';
    const locksmith = 'locksmith';
    const lodging = 'lodging';
    const meal_delivery = 'meal_delivery';
    const meal_takeaway = 'meal_takeaway';
    const mosque = 'mosque';
    const movie_rental = 'movie_rental';
    const movie_theater = 'movie_theater';
    const moving_company = 'moving_company';
    const museum = 'museum';
    const night_club = 'night_club';
    const painter = 'painter';
    const park = 'park';
    const parking = 'parking';
    const pet_store = 'pet_store';
    const pharmacy = 'pharmacy';
    const physiotherapist = 'physiotherapist';
    const plumber = 'plumber';
    const police = 'police';
    const post_office = 'post_office';
    const primary_school = 'primary_school';
    const real_estate_agency = 'real_estate_agency';
    const restaurant = 'restaurant';
    const roofing_contractor = 'roofing_contractor';
    const rv_park = 'rv_park';
    const school = 'school';
    const secondary_school = 'secondary_school';
    const shoe_store = 'shoe_store';
    const shopping_mall = 'shopping_mall';
    const spa = 'spa';
    const stadium = 'stadium';
    const storage = 'storage';
    const store = 'store';
    const subway_station = 'subway_station';
    const supermarket = 'supermarket';
    const synagogue = 'synagogue';
    const taxi_stand = 'taxi_stand';
    const tourist_attraction = 'tourist_attraction';
    const train_station = 'train_station';
    const transit_station = 'transit_station';
    const travel_agency = 'travel_agency';
    const university = 'university';
    const veterinary_care = 'veterinary_care';
    const zoo = 'zoo';

    const MAP = [
        'accounting'              => self::accounting,
        'airport'                 => self::airport,
        'amusement_park'          => self::amusement_park,
        'aquarium'                => self::aquarium,
        'art_gallery'             => self::art_gallery,
        'atm'                     => self::atm,
        'bakery'                  => self::bakery,
        'bank'                    => self::bank,
        'bar'                     => self::bar,
        'beauty_salon'            => self::beauty_salon,
        'bicycle_store'           => self::bicycle_store,
        'book_store'              => self::book_store,
        'bowling_alley'           => self::bowling_alley,
        'bus_station'             => self::bus_station,
        'cafe'                    => self::cafe,
        'campground'              => self::campground,
        'car_dealer'              => self::car_dealer,
        'car_rental'              => self::car_rental,
        'car_repair'              => self::car_repair,
        'car_wash'                => self::car_wash,
        'casino'                  => self::casino,
        'cemetery'                => self::cemetery,
        'church'                  => self::church,
        'city_hall'               => self::city_hall,
        'clothing_store'          => self::clothing_store,
        'convenience_store'       => self::convenience_store,
        'courthouse'              => self::courthouse,
        'dentist'                 => self::dentist,
        'department_store'        => self::department_store,
        'doctor'                  => self::doctor,
        'drugstore'               => self::drugstore,
        'electrician'             => self::electrician,
        'electronics_store'       => self::electronics_store,
        'embassy'                 => self::embassy,
        'fire_station'            => self::fire_station,
        'florist'                 => self::florist,
        'funeral_home'            => self::funeral_home,
        'furniture_store'         => self::furniture_store,
        'gas_station'             => self::gas_station,
        'gym'                     => self::gym,
        'hair_care'               => self::hair_care,
        'hardware_store'          => self::hardware_store,
        'hindu_temple'            => self::hindu_temple,
        'home_goods_store'        => self::home_goods_store,
        'hospital'                => self::hospital,
        'insurance_agency'        => self::insurance_agency,
        'jewelry_store'           => self::jewelry_store,
        'laundry'                 => self::laundry,
        'lawyer'                  => self::lawyer,
        'library'                 => self::library,
        'light_rail_station'      => self::light_rail_station,
        'liquor_store'            => self::liquor_store,
        'local_government_office' => self::local_government_office,
        'locksmith'               => self::locksmith,
        'lodging'                 => self::lodging,
        'meal_delivery'           => self::meal_delivery,
        'meal_takeaway'           => self::meal_takeaway,
        'mosque'                  => self::mosque,
        'movie_rental'            => self::movie_rental,
        'movie_theater'           => self::movie_theater,
        'moving_company'          => self::moving_company,
        'museum'                  => self::museum,
        'night_club'              => self::night_club,
        'painter'                 => self::painter,
        'park'                    => self::park,
        'parking'                 => self::parking,
        'pet_store'               => self::pet_store,
        'pharmacy'                => self::pharmacy,
        'physiotherapist'         => self::physiotherapist,
        'plumber'                 => self::plumber,
        'police'                  => self::police,
        'post_office'             => self::post_office,
        'primary_school'          => self::primary_school,
        'real_estate_agency'      => self::real_estate_agency,
        'restaurant'              => self::restaurant,
        'roofing_contractor'      => self::roofing_contractor,
        'rv_park'                 => self::rv_park,
        'school'                  => self::school,
        'secondary_school'        => self::secondary_school,
        'shoe_store'              => self::shoe_store,
        'shopping_mall'           => self::shopping_mall,
        'spa'                     => self::spa,
        'stadium'                 => self::stadium,
        'storage'                 => self::storage,
        'store'                   => self::store,
        'subway_station'          => self::subway_station,
        'supermarket'             => self::supermarket,
        'synagogue'               => self::synagogue,
        'taxi_stand'              => self::taxi_stand,
        'tourist_attraction'      => self::tourist_attraction,
        'train_station'           => self::train_station,
        'transit_station'         => self::transit_station,
        'travel_agency'           => self::travel_agency,
        'university'              => self::university,
        'veterinary_care'         => self::veterinary_care,
        'zoo'                     => self::zoo,
    ];
}