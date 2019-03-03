public class SSort{
    public static void main(String[] args) {
        double[] a = {1, 9, 4.5, 6.6, 5.7, -4.5};
        selectionSort(a);
        printArray(a);
    }

    public static void selectionSort(double[] arr) {
        for (int i = 0; i < arr.length - 1; i++) {
            double maxValue = arr[i];
            int maxIndex = i;   //注意这里下标用int不要用double

            for (int j = i + 1; j < arr.length; j++) {
                if (arr[j] > maxValue) {
                    maxValue = arr[j];
                    maxIndex = j;
                }
            }

            if (maxIndex != i) {
                arr[maxIndex] = arr[i];
                arr[i] = maxValue;
            }
        }
    }

    public static void printArray(double[] arr) {
        for (int i = 0; i < arr.length; i++) {
            System.out.print(arr[i] + " ");
        }
        System.out.println();
    }
}