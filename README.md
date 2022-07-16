# Test para obtener los datos del clima

Esta api fue construida para obtener los datos del clima dependiendo de la ciudad

#### Url de la api: http://test-app.test/api/current?query=<NOMBRE DE LA CIUDAD>

#### Respuesta:

````
{
"current": {
"is_day": "yes",
"precip": 0,
"humidity": 71,
"pressure": 1011,
"uv_index": 3,
"wind_dir": "SSW",
"feelslike": 7,
"cloudcover": 100,
"visibility": 10,
"wind_speed": 24,
"temperature": 10,
"wind_degree": 210,
"weather_code": 122,
"weather_icons": [
"https://assets.weatherstack.com/images/wsymbols01_png_64/wsymbol_0004_black_low_cloud.png"
],
"observation_time": "07:42 PM",
"weather_descriptions": [
"Overcast"
]
},
"request": {
"type": "City",
"unit": "m",
"query": "Buenos Aires, Argentina",
"language": "en"
},
"location": {
"lat": "-34.588",
"lon": "-58.673",
"name": "Buenos Aires",
"region": "Distrito Federal",
"country": "Argentina",
"localtime": "2022-07-16 16:42",
"utc_offset": "-3.0",
"timezone_id": "America/Argentina/Buenos_Aires",
"localtime_epoch": 1657989720
}
}
````

Nota: Solo tiene para procesar 250 requests al mes ya que es un acceso free
