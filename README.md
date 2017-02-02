# Schedule Planner App
#### Software Engineering Seminar
----

### Team
<ul>
<li>Kheiyasa Carter</li>
<li>Jeff Registre</li>
</ul>


### Requirements
The web application will generate schedules based on students potential classes for the next semester. The student will be able to pick from a variety of classes through a drop down menu with a list of courses offered for the upcoming semester. Once the classes are submitted, the schedules will be generated and the student will have the option to save and download their schedule as JPG or PNG. During the following semester the student can use the website to track their GPA and course grades in their classes.

### Design
Within our web application we will have a sign in, login, and log out page. Once the user has signed in or logged into the website they will be able to add a class in the class page in order to add classes for scheduling purposes. Once the class is added they will navigate to the schedule page where they will see a list of potential schedules for the next semester. During the following semester they will be able to use the individual class pages or the overview page in order to view their progression in the class or add a grade to see their current grade.

### Implementation
We will be developing the front-end using HTML, CSS, Javascript, Bootstrap, and possibly Reactjs. The back end of the website will be developed using the laravel framework as well as MySQL.

### Testing Evaluation
We will use other students to test out the application in order to see if the website meets the requirements. First we will get the student to create a schedule for the Fall semester. Then we will add their classes from this current semester as well as grades they have already received in this semester. This will help us to see if the program can determine accurate grade approximations. If possible we will add their classes and grades from the previous fall semester to determine if we can accurately calculate their course grade as well as overall GPA.

### Scheduling 

![Gantt Chart 1](/gant1.PNG)  

![Gantt Chart 1](/gant2.PNG)

### Usage
Create and configure a .env file following the format provided by [.env.example](./.env.example)

To create the tables.
```shell 
php artisan migrate 
```

And lastly to start the server.
```shell 
php artisan serve
```

## License
Licensed under the [MIT](./LICENSE.md) license.
