public class VarArgsDemo {
    public static void main(String... args) {
        printMax(34, 3, 3, 2, 56.5);
        printMax(new double[]{1, 2, 3});
        printMax();
        printMax(0.7);
        printMax(9.6);
        /**
         * printMax(new int[]{7});
         */
        printMax(new double[]{7});
        printMax2(1000, 0.8, 10);
        printMax2(999.99, 0.8, 10);

        //printArr(new int[]{9});
    }

    public static void printMax(double... numbers){
        if (numbers.length == 0) {
            System.out.println("No argument passed");
            return;
        }

        double result = numbers[0];

        for (int i = 1; i < numbers.length; i++) {
            if (numbers[i] > result)
                result = numbers[i];
        }

        System.out.println("The max value is " + result);
    }

    public static void printMax2(int a, double... numbers){
        printMax(numbers);
    }

    public static void printMax2(double a, double... numbers){
        printMax(numbers);
    }

    public static void printArr(double[] arr){}
}