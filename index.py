""" print ("new line of VS code")
print(8 * "\n")
print ("new line of VS code") """

""" print ("Welcome to ", end="") 
print ("Guru99", end = '!') """

""" DataTypes """

""" a = 100
print(a)
a="new"
print(a)

a="sam"

b=0
print (a+str(b)) """


""" f= 10000
print (f)

def someFunction():
f = 'this is text'
print(f)

someFunction()

print(f) """


""" f = 101
print(f) 

def someFunction():
   
    f = 'I am learning Python' 
    print(f) 
    
someFunction()

a="i am boared"
print(a)  
someFunction()

print(f)  """



""" f = 101
print(f) 

def someFunction():
   
    f = 'I am learning Python' 
    print(f) 
    
    a="i am boared"
    print(a)
someFunction()

 
someFunction()

print(f) """

""" f=10
print(f)

def someFunction():
    
    global f
    print(f)
    
    f="this is local function"
    
someFunction()
print(f) """

""" one = "one"
two = "two"

three = one + two
print(three)

hello = "hello"
world = "world"

helloworld = hello + " " +world
print (helloworld)

a,b = 3,4
print(a,b)
 """
""" a,b= 1,2
one = "one"

print(str(a)+one+str(b))

result = str(a)+ " " +one + " " + str(b)
print(result)

print(f"{a} {one} {b}") """

""" myString = "hello"
myFloat = 10.0
myInt = 20


if myString == "hello":
    print("String: %s " % myString )
    
    if isinstance(myFloat, float) and myFloat == 10.0:
        print("Float: %f" % myFloat)
        
        if isinstance(myInt,int) and myInt == 20:
            print ("Integer: %d" % myInt)
            
        
    tup = ('ram','shyam','hari','krishna','gopal')
    tup1 = (1,2,3,4,5)
    print(tup[1])
    print(tup1[1:4]) """


def calculatea_sum():  
    a = 19
    b = 19
    c = a + b
    print(c)

    calculatea_sum()

""" def calculatea_sum():  # Function header
    a = 19  # Indented properly
    b = 19
    c = a + b
    print(c)

calculatea_sum()  # Calling the function """



""" n = int(input("enter  first number:"))
m = int(input("enter the second number:"))

def misMatch():
    
    
    if ((n+m) >= 40):
        
        print(True)
        
        
        
        
        name = input("enter your name: ")
        
        print("your name is:",name)
        
    else:
        print(False)
        
misMatch() """



""" n = int(input("Enter the number of rows: "))

for i in range(n):
    print(" " * (n - i - 1) + "*" * (2 * i + 1)) """

"""    
n = int(input("Enter the number of rows: "))

for i in range(n):
    print("*" * (2 * i + 1) + 5 +" " * (n - i - 1) ) """

""" n = int(input("Enter the number of rows: "))

for i in range(n):
    # 'i' spaces at the beginning, then (2*(n-i)-1) stars
    print(" " * i + "*" * (2 * (n - i) - 1)) """

Dic = { 'Tim':10 , 'Charlie':15 , 'Brandon':11}

print(Dic['Tim'])

""" 
Dict = {'Tim': 18,'Charlie':12,'Tiffany':22,'Robert':25}	
print("Students Name: %s" % Dict.items()) """

""" 
Dict = {'Tim':10 , 'Charlie':15 ,'Hob':9, 'Brandon':11,'Bisop':4}
Boy = {'Charlie':10 , 'Jim':15, 'Hob':9}
girl = {'Tim':15}



Students = Dict.keys()
Students.sort()
for S in Students:
    print(":".join((S,str(Dict[S])))) """

# del Dict ['Tim']


# print(Dict)

""" 
for key in Boy.keys():
    if key in Dict.keys():
        print(True)
    else:
        print(False) """



""" 
for key in list(Boy.keys()):
    if key in list(Dict.keys()):
        print(True)
    else:
        print(False) """



""" print("student name: %s" % list(Dict.items())) """


""" StuX = Boy.copy()
StuY = girl.copy()

print(StuX)
print(StuY) """





