# What is kiroku?

Kiroku is a web app for tracking your baby privately in your own server.

All data is stored locally, there is absolutely no tracking, no calling home, nor metadata leaving your server.
You can now keep track of your baby without needing to sell your newborn's soul to a greedy data broker.

There is only one single thing that uses an external service: the chart system. Once I figure out how to install it locally I will update it. Merge requests to make it optional are also welcome!

# What does kiroku track?

You can easily keep track of the following items:
- Milk
  - Breastfeed time.
    - Left and right breasts are tracked separately so that you can keep them balanced.
    - You can input the time manually or use an integrated timer.
  - Pumped amount in ml per breast.
  - Formula drank in ml.
  - Pumped milk drank in ml.
- Pee and poop times and color of the poop.
- Generic text memo.

The interface is designed for smartphones and using emoji instead of text as much as possible to keep the interface simple and for easy, visual, reading.

There are also some useful statistics and graphs!

# How to use

## Main page (index.php)
There are 5 buttons on the top page:
- 🤱️ for breast-feeding.
- 🍼️ for bottle-feeding and pump usage.
- 🚼️ for diaper change.
- 📝️ for generic text notes.
- 📈 for graphs and statistics.

Below that there are 4 buttons to change the date. The date should show up in your own locale.
The rightmost button saying "A hoy" means "To today" in Spanish and is a shortcut to show the current date.

Below that there is a table with the currently selected day's recordings.
- The column "時間" ("hour" in Japanese) is the hour of the recording.
  - You can click on the hour to see more details about that hour and to be able to edit/delete entries.
- The second and third columns ("Teta", "Breast" in Spanish followed by "左", "left" and "右", "right"). This is the number of minutes of breast-feeding for each beast.
- The "Pump ⛽" column is the ml of milk pumped from each breast (again, "左", "left" and "右", "right").
- The "Bibe 🍼️" column (from "Biberón" in Spanish) shows the ml of milk bottle-fed to the baby.
  - For pump breastmilk it shows the emoji 🤱.
  - Formula milk shows no emoji if alone, or the 🍼️ emoji if there is also breastmilk in that hour).
- The "Pañal 🚼️" column ("diaper" in Spanish) shows pee and poop count.
- The "Notas" column is for the text memo and other information (like abnormal poop color, for example).

Please note that multiple entries in the same hour get added together (for example, feeding the baby 20ml twice during the same hour will show as 40ml in this page).

## Hour details (hora.php)

This page shows all the events with all the datails recorded for that specific hour.

You can see the following information in the table:
- The time with minutes and seconds in the first column.
- The type of event being recorded in "Evento".
- Data stored with that event (the type and meaning of the data depends on the event type).

You can edit one row at a time and save that row. Please do not edit multiple rows, only the row that you press the save button will be saved and everything else will be lost.

You can also delete events here by pressing the bomb icon.

## Breast-feeding page (teta.php)

Shows 2 independent counters for the left (Izquierda) and right (Derecha) breasts.

The buttons do as follows:
- ▶️ starts/continues the timer.
- ⏹️pauses the timer.
- ↩️ resets to 0 the timer.

You can also set manually the timer to whatever value in minutes you want in the textbox below (note that those are decimal values, not seconds. So "1 minute and a half" is "1.5", not "1.30").

You can also add a text memo in the "Notas" box.

"Hora de registro" is the recording time. You can leave it unset and the server will use the current time, or you can force it to a different time (for example if you forgot to record something in the past).

## Bottle-feeding (bibe.php)

In the "Cantidad" input box you can set the ml of milk.

You can chose from the following:
- The first row is for milk given to the baby:
  - 🥫🍼 for formula milk (think of it as "canned milk").
  - 💧👨‍🍼 for pumped breast-milk.
- The second row is for pump milk to be stored:
  - 💧⬇️ means "pumped and saved".
  - You can choose what breast was used for pumping: 左 (left) and/or 右 (right).
    - If both are selected, then the input ml is equally divided between both breasts! So if you wrote 40ml then the left one will be set to 20ml and the right one to the other 20ml.

At the top of the page there will be a guess of how many ml of pumped breast milk there is.

As with the breast-feeding, you can add a memo in "Notas" and set a specific recording time in "hora de registro" or leave it unset so that the current time is used instead.

## Diaper (caca.php)

You can select for poop 💩️ and/or pee 💧️.
If poop is selected, you can also choose the color of the poop. The first 3 colors, black and red are considered not good for the baby so a warning will show up in the main screen if those are selected.
Note that the baby's poop is black during the 1 to 2 weeks of life and that is normal.

As usual, you can optionally add a memo and set a time of recording in "notas" and "hora de registro". Leaving it empty will use the current time automatically.

## Memo (memo.php)

Allows you to add a standalone memo, similar to the other input pages.

## Chart (chart.php)

Shows useful/interesting information about the baby.

It is divided in 2 parts, first some statistics and later some graphs.

Please note that there is no caching yet, so it could take some time to compute all the statistics if you have a lot of data. Merge requests to improve this are welcome!

### Statistics

There are 3 columns:
- "24h" is for the information of the last 24 hours.
- "7d" is for the daily average of the last 7 days.
- "1mes" is for the daily average of the last month.

The rows mean the following:
- "Cantidad" is "quantity":
  - "Teta" is "breast" (左 is left, 右 is right).
  - "Bomba" is "pump".
  - "Fórmula" is "formula milk".
  - "Caca" is "poop".
  - "Pipi" is "pee".
- "Tiempo entre" means "time between" and measures the time that passes between one event and the next. Please note that for food events, two close events are considered as only one single feed. For example, if you give breast milk and shortly after you supplement with formula, those are considered one single feed event.
  - "Comidas" is "food" in general (pump, breast, formula are all considered as the same event).
  - "Teta" is "breast-feeding" events only.
  - "Formula" is formula-feeding events only.

In parenthesis the unit is shown:
- "回数" count, or number of times that event happened.
- "min" for minutes.
- "ml" for millilitres.
- "h" is for hours.

### Graphs

Currently there is only one graph that shows the monthly ratio of breast-feeding for each hour.
The vertical axis is the percentage (as in 1.00 would be 100%), and the horizontal axis is the time of the day.

## Simple poop/pee (simple.php)

This is a secret page that I use with a small touchscreen near the baby's changing station.
It can only add diaper information and it is meant to be used with something like firefox's kiosk mode.

# Requirements

You'll need:
- A web server (I use Apache2 but anything that can run php should be good).
- php7.4 or higher (maybe older also work but they are already in end of life anyway).
- A mysql server.

It is very lightweight, and most probably only a handful of people will be accessing it so any low-cost device like a raspberry-pi should be capable of hosting it. Remember that if you run things locally, you need to open ports etc for the webserver to be accesible from outside your network.

# How to install

1. Setup your webserver and mysql (I won't help with that, search online if you don't know how to do that).
1. Copy the files into your webserver web folder. I recommend putting everything in a subfolder called "kiroku".
1. Create a new mysql database called "kiroku".
1. Create a table called "eventlog" with the schema written below inside the "kiroku" database.
1. Create a new mysql user named "kiroku" with a new, long password and grant insert, update, delete, select to that table.
1. Rename config.default.php to config.php and put the password you created in the previous step.
1. Enjoy!

mysql setup:
```
create database kiroku;

use kiroku;

create table eventlog (uid int auto_increment primary key, what varchar(64) not null, data text, by_who varchar(64), logtime TIMESTAMP NOT NULL, canary int not null unique );

create user 'kiroku'@'localhost' identified by 'VERY DIFFICULT PASSWORD HERE';

grant insert, update, delete, select on kiroku.* to 'kiroku'@'localhost';

flush privileges;
```

# Authors

Alfonso Arbona Gimeno

Code is very welcome!
Bug reports too! I will fix them as much as I can.
Features will be implemented only if I have time and find them useful personally. Feel free to submit code in a merge request if you want a specific feature yourself!

# License

GNU General Public License v3.0

     This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

    This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

    You should have received a copy of the GNU General Public License along with this program. If not, see <https://www.gnu.org/licenses/>. 

Please see COPYING for more details.

# Work left to do

1. Unmix languages... Better if we can have some locale-dependent translation.
1. Security (need user/password).
1. Add statistic and graph caching.
1. Add more statistics and graphs.
1. Make it more visually appealing, specially on desktop.
1. Clean the style and css.

