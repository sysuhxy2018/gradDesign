package abc;

import java.util.Date;

public class TestLibrary{
    public static void main(String[] args) {
        testDate();
    }

    public static void testDate() {
        Date date = new Date();
        System.out.println(date.toString());
        System.out.println(date.getTime());

        long upperBound = (long)1.00E9; //科学计数法表示的只能是浮点数
        System.out.println(upperBound);
        //等待一小段时间
        for (long i = 0; i < upperBound; i++) {}
        System.out.println(date.toString());
        System.out.println(date.getTime());

        Date newDate = new Date(System.currentTimeMillis() - 1000 * 18);
        System.out.println(newDate.toString());
        System.out.println(newDate.getTime());
        newDate.setTime(newDate.getTime() + 1000L * 60 * 60 * 24 * 365); //这里不用long是得不到一年后的时间的
        System.out.println(newDate.toString());
        System.out.println(newDate.getTime());

    }

    int a = 0;
    static Welcome c = new Welcome();
}


