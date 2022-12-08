# Hotel\_\_backend-task

This is a backend task in this [github gist](https://gist.github.com/ahmed3mar/483fa6bf1f5bdb8bf58f37fcd538d068)

I worked on it using plain PHP, can't forget great help by [Eng. Hoda Hussin](https://github.com/hodaa) mentoring me and reviewing my progress.

## About

The task is to request several data providers, organize this data to be in the same format, keynames and so on, apply filtering, sorting and paginating.

It's now live on: https://hotels-php-amralaaeldin.000webhostapp.com/

`Note` This will return all data comes from the providers

I also build client side using React.js dealing with it with a basic, easy UI, you can visit it live on: https://hotels-client-amralaaeldin.vercel.app/
Code: [Hotel\_\_backend-task-client Repo](https://github.com/amralaaeldin/Hotel__backend-task-client)

## Parameters

| Parameter |                            Required ?                            |     Example      |               Supported Values               |
| :-------: | :--------------------------------------------------------------: | :--------------: | :------------------------------------------: |
|  format   |                    optional, default is json                     |   `format=xml`   |                  json, xml                   |
|   page    |             optional, default is without pagination              |     `page=1`     | integer numbers, data is within 2 pages here |
|   find    |                             optional                             |   `find=hotel`   |               any search word                |
|  sort-by  |      required if `sort-type` is present, otherwise optional      |  `sort-by=rate`  |                 rate, price                  |
| sort-type |       required if `sort-by` is present, otherwise optional       | `sort-type=desc` |                  asc, desc                   |
|  filter   |    required if `min` or `max` are present, otherwise optional    |  `filter=rate`   |                 rate, price                  |
|    min    | required if `filter` is present and no `max`, otherwise optional |     `min=3`      |      `rate`: 1 - 5, `price`: 0 - 20000       |
|    max    | required if `filter` is present and no `min`, otherwise optional |     `max=5`      |      `rate`: 1 - 5, `price`: 0 - 20000       |

`Example`: https://hotels-php-amralaaeldin.000webhostapp.com/?format=json&page=1&filter=rate&find=hotel&max=4&min=3&sort-by=rate&sort-type=desc
