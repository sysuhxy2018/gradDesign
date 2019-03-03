public class BSearch{
    public static void main(String[] args){
        System.out.println(binarySearch(new int[]{7, 10, 13}, 6));
        System.out.println(binarySearch(new int[]{7, 10, 13}, 8));
        System.out.println(binarySearch(new int[]{7, 10, 13}, 12));
        System.out.println(binarySearch(new int[]{7, 10, 13}, 14));

        System.out.println(binarySearch(new int[]{6, 6, 6, 6}, 6));
        System.out.println(binarySearch(new int[]{3, 4, 4, 5}, 4));
        System.out.println(binarySearch(new int[]{3, 4, 4, 5, 6}, 4));
    }

    public static int binarySearch(int[] list, int key) {
        int low = 0;
        int high = list.length - 1;

        while (low <= high) {
            int mid = (low + high) / 2;
            if (key < list[mid]) {
                high = mid - 1;
            }
            else if (key == list[mid]) {
                return mid;
            }
            else {
                low = mid + 1;
            }
        }

        return -low - 1;
    }
}