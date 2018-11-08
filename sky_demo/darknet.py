import sys

if len(sys.argv)<3:
    print("Too few parameters!")
else:
    a=int(sys.argv[1])
    b=int(sys.argv[2])
    print(a+b)