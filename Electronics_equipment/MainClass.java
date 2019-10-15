/*
 * This is the main class
 */
package Electronics_equipment;

/**
 *
 * @author Radi
 */
public class MainClass {
    public static void main(String[] args){
        ElectronicsEquipment ee=new ElectronicsEquipment("Walton", "Electronics Device");
        Computer computer=new Computer("Walton", "Electronics Device", "Walton 847", 2, 1200, 3);
        CellPhone cellphone=new CellPhone("Lava", "Electronics Device", "Walton 847", 200, 12.00, 3.1);
        ee.display();
        System.out.println("This is about computer information ");
        System.out.println(computer.display());
        System.out.println("This is about mobile information \n\n");
        System.out.println(cellphone.display());
    }
}
