public class Format{
    public static void main(String[] args){
        System.out.printf("%,12d %10.1f\n", 12345678, 12345678.263);
        System.out.printf("%,012d %10.1f\n", 12345678, 12345678.263);
        System.out.printf("%,-12d %-1f\n", 12345678, 12345678.263);
        //暂未找到一种可以同时兼容0和-的方式。
        //System.out.printf("%0-12d %f\n", 12345678, 12345678.263);
        double a = 400000;
        System.out.printf("%10.3e\n", a);
        //System.out.printf("%10.3e\n", 400000);
        System.out.printf("%.2f%%\n", 75.235);
        //System.out.printf("%0.2f%%\n", 75.235);
        System.out.println("80.99%");
    }
}