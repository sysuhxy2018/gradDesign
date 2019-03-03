public class TwoClass{
    TwoClass(){}
    public static void main(String[] args) {
        TestClass tc = new TestClass();
        //tc = null;
        System.out.println(tc.number);
        tc.testMethod();

        A a = new A("a");
        a.print();
    }
}

class TestClass{
    TwoClass tc = new TwoClass();
    int number;
    public void testMethod(){}
}

class A{
    String s;

    A(String newS) {
        s = newS;
    }

    public void print() {
        System.out.println(s);
    }
}