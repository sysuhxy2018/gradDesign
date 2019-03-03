import java.util.Arrays;

public class PlayArray{
    public static void main(String[] args) {

        //排序
        char[] chars = {'a', 'A', '4', 'F', 'D', 'P'};
        Arrays.sort(chars, 1, 3);
        Arrays.parallelSort(chars, 1, 3);
        System.out.println(chars);

        //二分查找
        chars = new char[]{'a', 'c', 'g', 'x', 'y', 'z'};
        System.out.println(Arrays.binarySearch(chars, 'g'));
        System.out.println(Arrays.binarySearch(chars, 't'));

        //是否严格相等
        int[] list1 = {2, 4, 7, 10};
        int[] list2 = {2, 4, 7, 10};
        int[] list3 = {4, 2, 7, 10};
        System.out.println(Arrays.equals(list1, list2));
        System.out.println(Arrays.equals(list2, list3));

        //填充数组&&将数组转化为字符串
        list1 = new int[]{2, 4, 7, 10};
        list2 = new int[]{2, 4, 7, 7, 7, 10};
        Arrays.fill(list1, 5);
        Arrays.fill(list2, 1, 5, 8);
        System.out.println(Arrays.toString(list1));
        System.out.println(Arrays.toString(list2));

        System.out.println(Arrays.toString(new char[]{'a', 'b', 'c'}));
        System.out.println(Arrays.toString(new String[]{"OK"}));
        System.out.println(Arrays.toString(new char[]{}));
        System.out.println(Arrays.toString(new double[10]));
        //System.out.println(Arrays.toString(int[] a));
        //System.out.println(Arrays.toString(null));
        int[] k = null;
        System.out.println(Arrays.toString(k));
    }

}