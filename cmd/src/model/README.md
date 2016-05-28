Класс Passenger - класс описывающий - сущность пассажира.
У него есть:
    свойства :
        public $currentFloor - этаж на котором он находится и с которого может сделать вызов лифта, когда лифт движется
        public $destFloor - этаж на который Passenger хочет добраться
    методы
    setFloor() - Passenger нажимает кнопки внутри  лифта
    callLift() - Passenger  вызывает лифт, находясь вне лифта, на этаже

   Вызовы методов влияют на состояние стека вызовов лифта Lift::callStack, устанавливают единицу для соответствующего номера этажа. Passenger формирует стек вызовов лифта.

   Также экземпляр Passenger является элементом массива стека PassengerStack.

Класс PassengerStack - стек пассажиров в лифте. 
Реализован в виде массива с соответствующей обработкой - добавить в стек, вытолкнуть из стека.
    свойства $limit - размер стека, задача = 4, но может инициализироваться на любое число
    $stack - массив элементов стека, каждый элемент стека - экземпляр(объект) Passenger
    методы : pop(), push(), top(), isEmpty(), countStack()
    PassengerStack контролирует загрузку, выгрузку элементов, поднимая соответствующие прерывания OverLimitException.
    На основе этих прерываний класс Lift генерирует исключения при работе с загрузкой, выгрузкой Passenger.
Класс Lift - наш лифт
    Свойства:
    $maxPassengers - максимальное количество пассажиров
    $callStack  - массив из количества элементов = количеству этажей, содержащий 0 - вызова на этот этаж нет, 1 - вызов есть
    $maxFloor - максимальное количество этажей            
    $currentFloor - состояние лифта, текущий этаж, на котором он находится
    $currentPassengers - текущее количество пассажиров в лифте - экземпляр PassengerStack с экземплярами Passenger
    $blockOverload  - тригер блокировки лифта по перегрузу
    $stateMoving  - направление движения лифта, два значения - up и down
    Методы :
    __construct - инициализирует лифт с заданными параметрами
    printCallStack() - вывод состояния CallStack
    
    printStateLift - вывод состояния лифта
    
    moveDestFloor() - метод, который принимает решения куда двигаться и вызывает методы moveUp() и moveDown(). 
    Общий алгоритм:
    Если триггер $blockOverload = false, иначе исключение LiftException по перегрузке, то работаем.
    а) если лифт движется вверх  $stateMoving = up и в стеке вызовов есть вызовы выше текущего этажа, лифт не меняет своего состония и продолжает обслуживать вызовы вверх по индексам в стеке. 
    б) если лифт движется вверх  $stateMoving = up и в стеке вызовов нет вызовов выше текущего этажа, но есть вызовы ниже текущего этажа, лифт меняет направление движения $stateMoving = down и начинает обслуживать вызовы вниз.
    То же самое в обратную сторону. 
    в) если лифт движется вниз  $stateMoving = down и в стеке вызовов есть вызовы ниже текущего этажа, лифт не меняет своего состония и продолжает обслуживать вызовы вниз по индексам в стеке. 
    б) если лифт движется вниз  $stateMoving = down и в стеке вызовов нет вызовов ниже текущего этажа, но есть вызовы выше текущего этажа, лифт меняет направление движения $stateMoving = up и начинает обслуживать вызовы вверх.
    Если стек вызовов CallStack пустой, лифт ничего не делает.
    Движение лифта вверх, вниз до заданного этажа, а также переключение направления движения в верхней и нижней точках выполняют методы moveUp() и moveDown().

    Метод getInLift() - загрузка пассажиров в лифт. На вход подается список экземпляров Passenger. C помощью метода push() PassengerStack загружаем пассажиров. Если происходит переполнение стека -  PassengerStack поднимает исключение  OverLimitException, а метод getInLift() перехватывает данное исключение и взводит триггер $blockOverload с помощью метода checkOverload(). Метод moveDestFloor() не начнет работать пока тригер будет true.

    Метод getOutLift() - выгрузка пассажиров в лифт.На вход подается список экземпляров Passenger. C помощью метода pop() PassengerStack dsuhe;ftv пассажиров. Если стек пустой -  PassengerStack поднимает исключение  OverLimitException. Наш метод  getOutLift() перехватывает это исключение и сообщает, что список пассажиров пуст.
Я не придумала, как можно было бы показать всю работу программы кратко, поэтому в классе Lift создала метод run(), который содержит в себе набор тестов для демонстрации. Сама программа реализована как команда консоли Symphony  - app/console passenger:lift. И для реализации самой команды - создан еще один класс PassengerLiftCommand, который создает экземпляр Lift и вызывает метод run().

Результаты работы на тестовом наборе следующие:
natali@Natali-System-Product-Name ~/projects/HomeworkBinary/HW2/cmd $ app/console passenger:lift
PHP Warning:  Module 'curl' already loaded in Unknown on line 0

Execute

inithialization 
Lift maxFloor from 0 to 8
Lift maxPassengers 4
---------Lift State------------
Lift currentFloor 0
Current quantity of passengers 0
Lift stateMoving up
blockOverload  bool(false)
----------------------------------
I am passenger from floor 0 to floor 5
I am passenger from floor 0 to floor 7
I am passenger from floor 0 to floor 6
I am passenger from floor 0 to floor 8
I am passenger from floor 0 to floor 6
Get into the Lift I am passenger from floor 0 to floor 5
Get into the Lift I am passenger from floor 0 to floor 7
Get into the Lift I am passenger from floor 0 to floor 6
Get into the Lift I am passenger from floor 0 to floor 8
Caught exception: Stack is full!
Press button 5
Press button 7
Press button 6
Press button 8
Press button 6
---------Lift State------------
Lift currentFloor 0
Current quantity of passengers 4
Lift stateMoving up
blockOverload  bool(true)
----------------------------------
Caught exception: Overload, lift moving is blocked. Check overload 

---------Lift State------------
Lift currentFloor 0
Current quantity of passengers 4
Lift stateMoving up
blockOverload  bool(false)
----------------------------------
---------Call Stack------------
Floor num 0,  0
Floor num 1,  0
Floor num 2,  0
Floor num 3,  0
Floor num 4,  0
Floor num 5,  1
Floor num 6,  1
Floor num 7,  1
Floor num 8,  1
-------------------------------
From current floor 0
Moving to 5
Lift got destination floor 5
Get out the Lift I am passenger from floor 0 to floor 5
Get out the Lift I am passenger from floor 0 to floor 7
---------Lift State------------
Lift currentFloor 5
Current quantity of passengers 2
Lift stateMoving up
blockOverload  bool(false)
----------------------------------
I am passenger from floor 0 to floor 8
Call lift from floor 0
---------Lift State------------
Lift currentFloor 5
Current quantity of passengers 2
Lift stateMoving up
blockOverload  bool(false)
----------------------------------
---------Call Stack------------
Floor num 0,  1
Floor num 1,  0
Floor num 2,  0
Floor num 3,  0
Floor num 4,  0
Floor num 5,  0
Floor num 6,  1
Floor num 7,  1
Floor num 8,  1
-------------------------------
From current floor 5
Moving to 6
Lift got destination floor 6
Get out the Lift I am passenger from floor 0 to floor 6
---------Lift State------------
Lift currentFloor 6
Current quantity of passengers 1
Lift stateMoving up
blockOverload  bool(false)
----------------------------------
---------Call Stack------------
Floor num 0,  1
Floor num 1,  0
Floor num 2,  0
Floor num 3,  0
Floor num 4,  0
Floor num 5,  0
Floor num 6,  0
Floor num 7,  1
Floor num 8,  1
-------------------------------
From current floor 6
Moving to 7
Lift got destination floor 7
Get out the Lift I am passenger from floor 0 to floor 7
---------Lift State------------
Lift currentFloor 7
Current quantity of passengers 0
Lift stateMoving up
blockOverload  bool(false)
----------------------------------
---------Call Stack------------
Floor num 0,  1
Floor num 1,  0
Floor num 2,  0
Floor num 3,  0
Floor num 4,  0
Floor num 5,  0
Floor num 6,  0
Floor num 7,  0
Floor num 8,  1
-------------------------------
From current floor 7
Moving to 8
Moving to 8
Lift got destination floor 8
Caught exception: Stack is empty!
---------Lift State------------
Lift currentFloor 8
Current quantity of passengers 0
Lift stateMoving down
blockOverload  bool(false)
----------------------------------
---------Call Stack------------
Floor num 0,  1
Floor num 1,  0
Floor num 2,  0
Floor num 3,  0
Floor num 4,  0
Floor num 5,  0
Floor num 6,  0
Floor num 7,  0
Floor num 8,  0
-------------------------------
From current floor 8
Moving to 0
Lift got destination floor 0
---------Lift State------------
Lift currentFloor 0
Current quantity of passengers 0
Lift stateMoving up
blockOverload  bool(false)
----------------------------------
---------Call Stack------------
Floor num 0,  0
Floor num 1,  0
Floor num 2,  0
Floor num 3,  0
Floor num 4,  0
Floor num 5,  0
Floor num 6,  0
Floor num 7,  0
Floor num 8,  0
-------------------------------
From current floor 0
Moving to 0
Lift got destination floor 0
---------Lift State------------
Lift currentFloor 0
Current quantity of passengers 0
Lift stateMoving up
blockOverload  bool(false)
----------------------------------
---------Call Stack------------
Floor num 0,  0
Floor num 1,  0
Floor num 2,  0
Floor num 3,  0
Floor num 4,  0
Floor num 5,  0
Floor num 6,  0
Floor num 7,  0
Floor num 8,  0
-------------------------------
Get into the Lift I am passenger from floor 0 to floor 8
Press button 8
---------Lift State------------
Lift currentFloor 0
Current quantity of passengers 1
Lift stateMoving up
blockOverload  bool(false)
----------------------------------
---------Call Stack------------
Floor num 0,  0
Floor num 1,  0
Floor num 2,  0
Floor num 3,  0
Floor num 4,  0
Floor num 5,  0
Floor num 6,  0
Floor num 7,  0
Floor num 8,  1
-------------------------------
I am passenger from floor 5 to floor 3
Call lift from floor 5
---------Lift State------------
Lift currentFloor 0
Current quantity of passengers 1
Lift stateMoving up
blockOverload  bool(false)
----------------------------------
---------Call Stack------------
Floor num 0,  0
Floor num 1,  0
Floor num 2,  0
Floor num 3,  0
Floor num 4,  0
Floor num 5,  1
Floor num 6,  0
Floor num 7,  0
Floor num 8,  1
-------------------------------
From current floor 0
Moving to 5
Lift got destination floor 5
Get into the Lift I am passenger from floor 5 to floor 3
Press button 3
---------Lift State------------
Lift currentFloor 5
Current quantity of passengers 2
Lift stateMoving up
blockOverload  bool(false)
----------------------------------
---------Call Stack------------
Floor num 0,  0
Floor num 1,  0
Floor num 2,  0
Floor num 3,  1
Floor num 4,  0
Floor num 5,  0
Floor num 6,  0
Floor num 7,  0
Floor num 8,  1
-------------------------------
From current floor 5
Moving to 8
Moving to 8
Lift got destination floor 8
---------Lift State------------
Lift currentFloor 8
Current quantity of passengers 2
Lift stateMoving down
blockOverload  bool(false)
----------------------------------
---------Call Stack------------
Floor num 0,  0
Floor num 1,  0
Floor num 2,  0
Floor num 3,  1
Floor num 4,  0
Floor num 5,  0
Floor num 6,  0
Floor num 7,  0
Floor num 8,  0
-------------------------------
Get out the Lift I am passenger from floor 0 to floor 8
---------Lift State------------
Lift currentFloor 8
Current quantity of passengers 1
Lift stateMoving down
blockOverload  bool(false)
----------------------------------
---------Call Stack------------
Floor num 0,  0
Floor num 1,  0
Floor num 2,  0
Floor num 3,  1
Floor num 4,  0
Floor num 5,  0
Floor num 6,  0
Floor num 7,  0
Floor num 8,  0
-------------------------------
From current floor 8
Moving to 3
Lift got destination floor 3
---------Lift State------------
Lift currentFloor 3
Current quantity of passengers 1
Lift stateMoving down
blockOverload  bool(false)
----------------------------------
---------Call Stack------------
Floor num 0,  0
Floor num 1,  0
Floor num 2,  0
Floor num 3,  0
Floor num 4,  0
Floor num 5,  0
Floor num 6,  0
Floor num 7,  0
Floor num 8,  0
-------------------------------



















